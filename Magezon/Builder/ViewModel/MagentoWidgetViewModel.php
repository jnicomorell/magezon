<?php
namespace Magezon\Builder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data;
use Magezon\Builder\Block\Element;

class MagentoWidgetViewModel implements ArgumentInterface
{
    protected $helper;
    protected $block;

    public function __construct(Data $helper, Element $block)
    {
        $this->helper = $helper;
        $this->block = $block;
    }

    public function getFilteredWidgetHtml()
    {
        $element = $this->block->getElement();
        $html = $element->getData('magento_widget');
        return $this->helper->filter($html);
    }
}
