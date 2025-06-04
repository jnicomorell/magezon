<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Helper\Data as BuilderHelper;
use Magezon\PageBuilder\Block\Element\FlipBox as Block;

class FlipBoxData implements ArgumentInterface
{
    private CoreHelper $coreHelper;
    private BuilderHelper $builderHelper;
    private Block $block;

    public function __construct(
        CoreHelper $coreHelper,
        BuilderHelper $builderHelper,
        Block $block
    ) {
        $this->coreHelper = $coreHelper;
        $this->builderHelper = $builderHelper;
        $this->block = $block;
    }

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function filter(?string $text): string
    {
        return $this->coreHelper->filter($text ?? '');
    }

    public function getImageUrl(?string $image): string
    {
        return $image ? $this->builderHelper->getImageUrl($image) : '';
    }

    public function getBlock(): Block
    {
        return $this->block;
    }
}
