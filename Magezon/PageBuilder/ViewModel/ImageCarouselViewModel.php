<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Helper\Data as BuilderHelper;
use Magezon\PageBuilder\Block\Element\ImageCarousel as Block;

class ImageCarouselViewModel implements ArgumentInterface
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

    public function getItems(): array
    {
        return $this->block->toObjectArray($this->getElement()->getItems());
    }

    public function getOwlCarouselOptions(): array
    {
        return $this->block->getOwlCarouselOptions();
    }

    public function getOwlCarouselClasses(): array
    {
        return $this->block->getOwlCarouselClasses();
    }

    public function getSize(): array
    {
        return $this->block->getsize();
    }

    public function getImage(string $src): string
    {
        return $this->block->getImage($src);
    }

    public function getLinkParams($data): array
    {
        return $this->block->getLinkParams($data);
    }

    public function getImageUrl(string $path): string
    {
        return $this->builderHelper->getImageUrl($path);
    }

    public function filter(string $text): string
    {
        return $this->coreHelper->filter($text);
    }

    public function getSerializedOptions(array $options): string
    {
        return $this->coreHelper->serialize($options);
    }

    public function getBlock(): Block
    {
        return $this->block;
    }
}
