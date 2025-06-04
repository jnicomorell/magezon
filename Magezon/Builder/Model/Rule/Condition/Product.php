<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_Builder
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

namespace Magezon\Builder\Model\Rule\Condition;

use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\RequestInterface;

class Product extends AbstractCondition
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        EavConfig $eavConfig,
        RequestInterface $request,
        array $data = []
    ) {
        $this->eavConfig = $eavConfig;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    /**
     * @inheritdoc
     */
    public function loadAttributeOptions()
    {
        if ($this->request->getParam('mgz_builder')) {
            $attributes = [];

            $productEntityType = 'catalog_product';
            $productAttributes = $this->eavConfig->getEntityAttributes($productEntityType);

            foreach ($productAttributes as $attribute) {
                $label = $attribute->getFrontendLabel();
                if ($label) {
                    $attributes[$attribute->getAttributeCode()] = $label;
                }
            }

            $this->_addSpecialAttributes($attributes);

            asort($attributes);
            $this->setAttributeOption($attributes);
            return $this;
        }

        $this->setAttributeOption([]);
        return $this;
    }

    /**
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function _addSpecialAttributes(&$attributes)
    {
        $attributes['category_ids'] = __('Category');
        $attributes['sku'] = __('SKU');
    }
}
