<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\PageBuilder\Block\Element\ContentSlider as Block;

class ContentSlider implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private Block $block
    ) {}

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function filter(?string $text): string
    {
        return $this->coreHelper->filter($text ?? '');
    }

    public function getSerializedOptions(array $options): string
    {
        return $this->coreHelper->serialize($options);
    }
}
