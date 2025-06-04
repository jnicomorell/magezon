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
use Magezon\Core\Model\ProductList;
use Magezon\Core\Helper\Data as CoreHelper;

/**
 * Product grid block for page builder.
 */
class ProductGrid extends \Magezon\Builder\Block\ListProduct
{
    /**
     * @var ProductList
     */
    private $productList;

    /**
     * @var CoreHelper
     */
    private $coreHelper;

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
     * @param ProductList $productList
     * @param CoreHelper $coreHelper
     * @param array $data
     */
    public function __construct(
        HttpContext $httpContext,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        DesignInterface $design,
        ProductList $productList,
        CoreHelper $coreHelper,
        array $data = []
    ) {
        parent::__construct($data);
        $this->httpContext = $httpContext;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->design = $design;
        $this->productList = $productList;
        $this->coreHelper = $coreHelper;
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
            'MGZ_BUILDERS_PRODUCT_GRID',
            $this->priceCurrency->getCurrencySymbol(),
            $this->storeManager->getStore()->getId(),
            $this->design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            (int) $this->getRequest()->getParam($this->getData('page_var_name'), 1),
            $this->coreHelper->serialize($this->getRequest()->getParams()),
            $this->getData('element_id'),
            $this->getData('element_type')
        ];
    }

    /**
     * Get product items.
     *
     * @return array
     */
    public function getItems(): array
    {
        $element = $this->getElement();
        $order = $element->getData('orer_by');
        $totalItems = (int) $element->getData('max_items');
        $items = $this->productList->getProductCollection(
            $element->getSource(),
            $totalItems,
            $order,
            $element->getData('condition')
        );

        $count = count($items);
        $itemsPerPage = (int) $element->getData('items_per_page') ?: 1;
        $displayStyle = $element->getData('display_style');

        if ($displayStyle === 'pagination') {
            $totalPages = ($count % $itemsPerPage === 0) ? ($count / $itemsPerPage) : (int) ($count / $itemsPerPage) + 1;
        } else {
            $totalPages = $count;
        }

        $newItems = [];
        $currentPage = 0;
        $index = 0;
        foreach ($items as $item) {
            $newItems[$currentPage][] = $item;
            if ($index === $itemsPerPage - 1 && $displayStyle === 'pagination') {
                $currentPage++;
                $index = 0;
                continue;
            }
            $index++;
        }

        return $newItems;
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
        $styleHtml = $this->getOwlCarouselStyles();
        $styleHtml .= $this->getLineStyles();
        return $styleHtml;
    }
}
