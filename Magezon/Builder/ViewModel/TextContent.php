<?php
namespace Magezon\Builder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Block\Element;

class TextContent implements ArgumentInterface
{
    protected $coreHelper;
    protected $block;

    public function __construct(
        CoreHelper $coreHelper,
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        $this->coreHelper = $coreHelper;
        $this->block = $context->getView()->getLayout()->getBlock('magezon.builder.text');
    }

    public function getFilteredContent()
    {
        if (!$this->block || !method_exists($this->block, 'getElement')) {
            return '';
        }

        $element = $this->block->getElement();
        $content = $element->getData('content');
        $content = str_replace('<p></p>', '<br/>', $content);
        return $this->coreHelper->filter($content);
    }
}
