<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Helper\Data as BuilderHelper;

class Testimonials implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private BuilderHelper $builderHelper
    ) {
    }

    public function getSerializedOptions(array $options): string
    {
        return $this->coreHelper->serialize($options);
    }

    public function filter(string $content): string
    {
        return $this->coreHelper->filter($content);
    }

    public function getImageUrl(string $image): string
    {
        return $this->builderHelper->getImageUrl($image);
    }
}
