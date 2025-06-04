<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\PageBuilder\Block\Element\PricingTable;

class PricingTableData implements ArgumentInterface
{
    public function __construct(private PricingTable $block)
    {
    }

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function toObjectArray($items): array
    {
        return $this->block->toObjectArray($items);
    }

    public function getLinkParams($linkData): array
    {
        return $this->block->getLinkParams($linkData);
    }
}
