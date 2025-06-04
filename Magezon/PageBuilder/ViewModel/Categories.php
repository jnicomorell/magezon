<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;

class Categories implements ArgumentInterface
{
    private CoreHelper $coreHelper;

    public function __construct(CoreHelper $coreHelper)
    {
        $this->coreHelper = $coreHelper;
    }

    public function filter(?string $content): string
    {
        return $content ? $this->coreHelper->filter($content) : '';
    }
}
