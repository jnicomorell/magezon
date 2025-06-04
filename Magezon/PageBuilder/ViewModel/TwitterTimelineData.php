<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Builder\Helper\Data as BuilderHelper;

class TwitterTimelineData implements ArgumentInterface
{
    public function __construct(
        private BuilderHelper $builderHelper
    ) {}

    public function getStyleColor(?string $value): string
    {
        return $this->builderHelper->getStyleColor($value ?? '');
    }
}

