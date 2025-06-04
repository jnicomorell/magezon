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
use Magezon\Core\Model\ProductList as ProdList;
use Magezon\Core\Helper\Data as CoreHelper;

/**
 * Product list block for page builder.
 */
class ProductList extends \Magezon\Builder\Block\ListProduct
{
    /**
     * @var ProdList
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
     * @param ProdList $productList
     * @param CoreHelper $coreHelper
     * @param array $data
     */
    public function __construct(
        HttpContext $httpContext,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        DesignInterface $design,
        ProdList $productList,
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
            'MGZ_BUILDERS_PRODUCT_LIST',
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
     * Get product collection.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getItems()
    {
        $element = $this->getElement();
        $order = $element->getData('orer_by');
        $totalItems = (int) $element->getData('max_items');
        return $this->productList->getProductCollection(
            $element->getSource(),
            $totalItems,
            $order,
            $element->getData('condition')
        );
    }

    /**
     * Get additional styles HTML.
     *
     * @return string
     */
    public function getAdditionalStyleHtml(): string
    {
        return $this->getLineStyles();
    }
}
