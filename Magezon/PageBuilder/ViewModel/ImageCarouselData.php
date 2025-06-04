<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Helper\Data as BuilderHelper;
use Magezon\PageBuilder\Block\Element\ImageCarousel as ImageCarouselBlock;

class ImageCarouselData implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private BuilderHelper $builderHelper,
        private ImageCarouselBlock $block
    ) {}

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function filter(?string $text): string
    {
        return $this->coreHelper->filter($text ?? '');
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

    public function getImage(string $path): string
    {
        return $this->block->getImage($path);
    }

    public function getImageUrl(string $path): string
    {
        return $this->builderHelper->getImageUrl($path);
    }

    public function serialize(array $data): string
    {
        return $this->coreHelper->serialize($data);
    }

    public function getSize(): array
    {
        return $this->block->getsize();
    }
}
