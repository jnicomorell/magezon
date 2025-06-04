<?php

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\PageBuilder\Block\Element\Icon as IconBlock;

class IconViewModel implements ArgumentInterface
{
    /**
     * @var CoreHelper
     */
    private $coreHelper;

    /**
     * @var IconBlock
     */
    private $block;

    public function __construct(
        CoreHelper $coreHelper,
        IconBlock $block
    ) {
        $this->coreHelper = $coreHelper;
        $this->block = $block;
    }

    /**
     * Return current element
     */
    public function getElement()
    {
        return $this->block->getElement();
    }

    /**
     * Wrapper for block getLinkParams
     */
    public function getLinkParams($data)
    {
        return $this->block->getLinkParams($data);
    }
}
