<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;

class IconList implements ArgumentInterface
{
    public function __construct(private CoreHelper $coreHelper) {}

    public function getFilteredText(string $text): string
    {
        return $this->coreHelper->filter($text);
    }
}
