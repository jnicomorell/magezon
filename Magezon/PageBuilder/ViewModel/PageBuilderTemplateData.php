<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\PageBuilder\Helper\Data;
use Magezon\PageBuilder\Block\Element\PageBuilderTemplate;

class PageBuilderTemplateData implements ArgumentInterface
{
    private Data $helper;
    private PageBuilderTemplate $block;

    public function __construct(
        Data $helper,
        PageBuilderTemplate $block
    ) {
        $this->helper = $helper;
        $this->block = $block;
    }

    public function getFilteredProfile(): string
    {
        $template = $this->block->getPageBuilderTemplate();
        return $this->helper->filter($template->getProfile());
    }
}
