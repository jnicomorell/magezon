<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\PageBuilder\Block\Element\RecentReviews as RecentReviewsBlock;

class RecentReviews implements ArgumentInterface
{
    /**
     * @var CoreHelper
     */
    private $helper;

    /**
     * @var RecentReviewsBlock
     */
    private $block;

    public function __construct(CoreHelper $helper, RecentReviewsBlock $block)
    {
        $this->helper = $helper;
        $this->block  = $block;
    }

    public function filter(string $text): string
    {
        return $this->helper->filter($text);
    }

    public function serialize($data = []): string
    {
        return $this->helper->serialize($data);
    }

    public function getElement()
    {
        return $this->block->getElement();
    }
}
