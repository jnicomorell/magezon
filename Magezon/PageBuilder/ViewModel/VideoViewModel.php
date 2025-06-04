<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Helper\Data as BuilderHelper;
use Magezon\PageBuilder\Block\Element\Video;

class VideoViewModel implements ArgumentInterface
{
    /**
     * @var CoreHelper
     */
    protected $coreHelper;

    /**
     * @var BuilderHelper
     */
    protected $builderHelper;

    /**
     * @var Video
     */
    protected $block;

    public function __construct(
        CoreHelper $coreHelper,
        BuilderHelper $builderHelper,
        Video $block
    ) {
        $this->coreHelper = $coreHelper;
        $this->builderHelper = $builderHelper;
        $this->block = $block;
    }

    public function filter($string)
    {
        return $this->coreHelper->filter($string);
    }

    public function getImageUrl($path)
    {
        return $this->builderHelper->getImageUrl($path);
    }

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function getBlock()
    {
        return $this->block;
    }
}
