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
use Magezon\Core\Model\ProductListFactory;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\PageBuilder\ViewModel\ProductSlider as ViewModel;

/**
 * Product slider block for page builder.
 */
class ProductSlider extends \Magezon\Builder\Block\ListProduct
{
    /**
     * @var ProductListFactory
     */
    private $productListFactory;

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
     * @var ViewModel
     */
    private ViewModel $viewModel;

    /**
     * Constructor.
     *
     * @param HttpContext $httpContext
     * @param PriceCurrencyInterface $priceCurrency
     * @param StoreManagerInterface $storeManager
     * @param DesignInterface $design
     * @param ProductListFactory $productListFactory
     * @param CoreHelper $coreHelper
     * @param array $data
     */
    public function __construct(
        HttpContext $httpContext,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        DesignInterface $design,
        ProductListFactory $productListFactory,
        CoreHelper $coreHelper,
        ViewModel $viewModel,
        array $data = []
    ) {
        parent::__construct($data);
        $this->httpContext = $httpContext;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->design = $design;
        $this->productListFactory = $productListFactory;
        $this->coreHelper = $coreHelper;
        $this->viewModel = $viewModel;
    }

    public function getViewModel(): ViewModel
    {
        return $this->viewModel;
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
            'MGZ_BUILDERS_PRODUCT_SLIDER',
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
        $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        $element = $this->getElement();
        $order = $element->getData('orer_by');
        $totalItems = (int) $element->getData('max_items');
        $isShowOutOfStock = (int) $element->getData('show_out_of_stock');

        $items = $this->productListFactory->create()->getProductCollection(
            $element->getSource(),
            $totalItems,
            $order,
            $element->getData('condition'),
            $storeId,
            $isShowOutOfStock
        );

        $count = count($items);
        $collection = [];
        $itemsPerColumn = (int) $element->getData('items_per_column') ?: 1;

        $column = ($count % $itemsPerColumn === 0)
            ? ($count / $itemsPerColumn)
            : (int) ($count / $itemsPerColumn) + 1;

        $i = $x = 0;
        foreach ($items as $_item) {
            if ($i < $column) {
                $i++;
            } else {
                $i = 1;
                $x++;
            }
            $collection[$i][$x] = $_item;
        }

        return $collection;
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
