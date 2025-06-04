<?php
declare(strict_types=1);

namespace Magezon\PageBuilderIconBox\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;

class IconBoxData implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper
    ) {}

    public function getFilteredTitle(array $element): string
    {
        return $this->coreHelper->filter($element['title'] ?? '');
    }

    public function getLinkParams(string $urlData): array
    {
        return $this->coreHelper->getLinkParams($urlData);
    }
}
