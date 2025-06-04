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
use Magento\Catalog\Model\ProductFactory;

/**
 * Single Product block for page builder.
 */
class SingleProduct extends \Magezon\Builder\Block\ListProduct
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var \Magento\Catalog\Model\Product|false|null
     */
    private $product = null;

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
     * @param ProductFactory $productFactory
     * @param array $data
     */
    public function __construct(
        HttpContext $httpContext,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        DesignInterface $design,
        ProductFactory $productFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->httpContext = $httpContext;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->design = $design;
        $this->productFactory = $productFactory;
    }

    /**
     * Check if the block is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->getProduct() && parent::isEnabled();
    }

    /**
     * Get the product instance.
     *
     * @return \Magento\Catalog\Model\Product|false
     */
    public function getProduct()
    {
        if ($this->product === null) {
            $element = $this->getElement();
            $sku = $element->getData('product_sku');
            if ($sku) {
                $product = $this->productFactory->create()->loadByAttribute('sku', $sku);
                $this->product = $product ?: false;
            } else {
                $this->product = false;
            }
        }
        return $this->product;
    }

    /**
     * Get additional styles HTML.
     *
     * @return string
     */
    public function getAdditionalStyleHtml(): string
    {
        $element = $this->getElement();
        $styleHtml = $this->getLineStyles();
        $styles = [
            'border-color' => $this->getStyleColor($element->getData('border_hover_color'), true)
        ];
        $_stylesHtml = $this->parseStyles($styles);
        if ($_stylesHtml) {
            $styleHtml .= '.' . $element->getHtmlId() . ' .product-item-info:hover{';
            $styleHtml .= $_stylesHtml;
            $styleHtml .= '}';
        }
        return $styleHtml;
    }
}
