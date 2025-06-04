<?php
namespace Magezon\Builder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Builder\Helper\Data;
use Magezon\Builder\Block\Element\SingleImage;

class SingleImageViewModel implements ArgumentInterface
{
    protected $helper;
    protected $block;

    public function __construct(Data $helper, SingleImage $block)
    {
        $this->helper = $helper;
        $this->block = $block;
    }

    public function getImageUrl($path)
    {
        return $this->helper->getImageUrl($path);
    }

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function getBlock()
    {
        return $this->block;
    }
}
