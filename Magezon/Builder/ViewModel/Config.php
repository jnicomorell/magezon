<?php
namespace Magezon\Builder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Builder\Helper\Data;

class Config implements ArgumentInterface
{
    protected $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function getRowInnerWidth()
    {
        return $this->helper->getConfig('general/row_inner_width');
    }

    public function getCustomCss()
    {
        return $this->helper->getConfig('customization/css');
    }
}
