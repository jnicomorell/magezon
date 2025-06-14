<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magento\Framework\View\LayoutInterface;

class RawHtmlData implements ArgumentInterface
{
    private CoreHelper $coreHelper;
    private ?\Magento\Framework\View\Element\Template $block;

    public function __construct(
        CoreHelper $coreHelper,
        LayoutInterface $layout
    ) {
        $this->coreHelper = $coreHelper;
        $this->block = $layout->getBlock('magezon.pagebuilder.raw_html');
    }

    public function filter(string $content): string
    {
        return $this->coreHelper->filter($content);
    }

    public function getBlock(): ?\Magento\Framework\View\Element\Template
    {
        return $this->block;
    }
}
