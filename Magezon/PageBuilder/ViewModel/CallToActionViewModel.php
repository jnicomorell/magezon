<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Helper\Data as BuilderHelper;
use Magezon\PageBuilder\Block\Element\CallToAction;

class CallToActionViewModel implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private BuilderHelper $builderHelper,
        private CallToAction $block
    ) {
    }

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function getFilteredText(?string $text): string
    {
        return $this->coreHelper->filter(trim((string) $text));
    }

    public function getImageUrl(?string $path): string
    {
        return $this->builderHelper->getImageUrl($path);
    }

    public function getLinkParams($data): array
    {
        return $this->block->getLinkParams($data);
    }

    public function getBlock(): CallToAction
    {
        return $this->block;
    }
}
