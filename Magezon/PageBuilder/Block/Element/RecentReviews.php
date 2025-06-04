<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs, please refer to https://www.magezon.com for more information.
 *
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

declare(strict_types=1);

namespace Magezon\PageBuilder\Block\Element;

use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\DesignInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magento\Review\Model\ResourceModel\Review\Collection as ReviewCollection;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

/**
 * Recent Reviews block for page builder.
 */
class RecentReviews extends \Magezon\Builder\Block\AbstractProduct
{
    /**
     * @var ReviewCollection
     */
    private $reviewsCollection;

    /**
     * @var CoreHelper
     */
    private $coreHelper;

    /**
     * @var ReviewCollectionFactory
     */
    private $reviewsColFactory;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var DesignInterface
     */
    private $design;

    /**
     * Constructor.
     *
     * @param HttpContext $httpContext
     * @param PriceCurrencyInterface $priceCurrency
     * @param StoreManagerInterface $storeManager
     * @param DesignInterface $design
     * @param CoreHelper $coreHelper
     * @param ReviewCollectionFactory $collectionFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        HttpContext $httpContext,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        DesignInterface $design,
        CoreHelper $coreHelper,
        ReviewCollectionFactory $collectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->httpContext = $httpContext;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->design = $design;
        $this->coreHelper = $coreHelper;
        $this->reviewsColFactory = $collectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => [\Magento\Catalog\Model\Product::CACHE_TAG]
        ]);
    }

    /**
     * Get cache key informative items.
     *
     * @return array
     */
    public function getCacheKeyInfo(): array
    {
        return [
            'MGZ_BUILDERS_RECENT_REVIEWS',
            $this->priceCurrency->getCurrencySymbol(),
            $this->storeManager->getStore()->getId(),
            $this->design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            $this->getData('element_id'),
            $this->getData('element_type')
        ];
    }

    /**
     * Get collection of reviews.
     *
     * @return ReviewCollection
     */
    public function getReviewsCollection(): ReviewCollection
    {
        if ($this->reviewsCollection === null) {
            $element = $this->getElement();
            $reviewsCount = (int) $element->getData('max_items') ?: 5;

            $collection = $this->reviewsColFactory->create()
                ->addStoreFilter($this->storeManager->getStore()->getId())
                ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
                ->setPageSize($reviewsCount);

            if ($element->getData('product_id')) {
                $collection->addFieldToFilter('entity_pk_value', $element->getData('product_id'));
            }

            $collection->setDateOrder()->addRateVotes();

            $productIds = $element->getData('product_id')
                ? [$element->getData('product_id')]
                : $collection->getColumnValues('entity_pk_value');

            $productCollection = $this->productCollectionFactory->create();
            $productCollection->addAttributeToSelect($this->_catalogConfig->getProductAttributes());
            $productCollection->addFieldToFilter('entity_id', ['in' => $productIds]);

            foreach ($collection as $review) {
                $product = $productCollection->getItemById($review->getEntityPkValue());
                if ($product) {
                    $review->setProduct($product);
                }
            }

            $this->reviewsCollection = $collection;
        }

        return $this->reviewsCollection;
    }

    /**
     * Get review content with length handling.
     *
     * @param \Magento\Review\Model\Review $review
     * @return string
     */
    public function getReviewContent(\Magento\Review\Model\Review $review): string
    {
        $element = $this->getElement();
        $detail = $review->getDetail(); // método dinámico (funciona porque está en review_detail)
        $content = $detail;
        $reviewContentLength = $element->getData('review_content_length');
        if ($reviewContentLength) {
            $content = $this->coreHelper->substr($content, $reviewContentLength);
            if ((strlen($content) + 1) === strlen($detail)) {
                $content = $detail;
            } else {
                $content .= '...';
            }
        }
        return $content;
    }

    /**
     * Get HTML id selector.
     *
     * @return string
     */
    public function getHtmlId(): string
    {
        return '.mgz-element.' . $this->getElement()->getHtmlId() . ' .owl-carousel';
    }

    /**
     * Get additional styles HTML.
     *
     * @return string
     */
    public function getAdditionalStyleHtml(): string
    {
        $element = $this->getElement();
        $id = $element->getId();
        $styleHtml = $this->getOwlCarouselStyles();
        $styleHtml .= $this->getLineStyles();

        $styles = [];
        $styles['color'] = $this->getStyleColor($element->getData('review_color'));
        $styles['background-color'] = $this->getStyleColor($element->getData('review_background_color'));
        $styleHtml .= $this->getStyles('.mgz-review-item', $styles);

        $styles = [];
        $styles['color'] = $this->getStyleColor($element->getData('review_link_color'));
        $styleHtml .= $this->getStyles('.mgz-review-item a', $styles);

        if ($element->getData('equal_height')) {
            $styleHtml .= '.' . $id . ' .owl-stage{';
            $styleHtml .= $this->getFixedStyleProperty('flex');
            $styleHtml .= '}';
        }

        if ($element->getData('review_star_color')) {
            $styles = ['color' => $this->getStyleColor($element->getData('review_star_color'))];
            $styleHtml .= $this->getStyles(['.rating-result > span'], $styles, ':before');
        }

        return $styleHtml;
    }
}
