<?php
namespace Magezon\Builder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data;
use Magezon\Builder\Block\Element;

class HeadingViewModel implements ArgumentInterface
{
    protected $helper;
    protected $block;

    public function __construct(Data $helper, Element $block)
    {
        $this->helper = $helper;
        $this->block = $block;
    }

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function getFilteredText($text)
    {
        return $this->helper->filter($text);
    }

    public function getLinkParams($linkData)
    {
        return $this->block->getLinkParams($linkData);
    }

    public function getBlock()
    {
        return $this->block;
    }
}
