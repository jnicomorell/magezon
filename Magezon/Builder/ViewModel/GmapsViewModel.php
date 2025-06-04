<?php
namespace Magezon\Builder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Builder\Helper\Data;
use Magezon\Builder\Block\Element\Gmaps;

class GmapsViewModel implements ArgumentInterface
{
    protected $helper;
    protected $block;

    public function __construct(Data $helper, Gmaps $block)
    {
        $this->helper = $helper;
        $this->block = $block;
    }

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function getGoogleMapApi()
    {
        return $this->helper->getGoogleMapApi();
    }

    public function getItems()
    {
        return $this->block->getItems();
    }

    public function getCenterItem()
    {
        return $this->block->getCenterItem();
    }
}
