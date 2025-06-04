<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Helper\Data as BuilderHelper;
use Magezon\PageBuilder\Block\Element\Slider as SliderBlock;

class SliderViewModel implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private BuilderHelper $builderHelper,
        private SliderBlock $block
    ) {}

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function getSlides(): array
    {
        return $this->block->toObjectArray($this->getElement()->getItems());
    }

    public function getHtmlId(): string
    {
        return $this->getElement()->getHtmlId();
    }

    public function getOwlCarouselOptions(): array
    {
        return $this->block->getOwlCarouselOptions();
    }

    public function getOwlCarouselClasses(): array
    {
        return $this->block->getOwlCarouselClasses();
    }

    public function getLinkParams($data): array
    {
        return $this->block->getLinkParams($data);
    }

    public function getIframeSrc(array $slide): string
    {
        return $this->block->getIframeSrc($slide);
    }

    public function getImageUrl(string $path): string
    {
        return $this->builderHelper->getImageUrl($path);
    }

    public function serialize(array $data): string
    {
        return $this->coreHelper->serialize($data);
    }
}
