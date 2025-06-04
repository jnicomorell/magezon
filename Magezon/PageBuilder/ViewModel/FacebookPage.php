<?php
namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Builder\Helper\Data as BuilderHelper;

class FacebookPage implements ArgumentInterface
{
    /**
     * @var BuilderHelper
     */
    protected $builderHelper;

    public function __construct(BuilderHelper $builderHelper)
    {
        $this->builderHelper = $builderHelper;
    }

    public function getStyleProperty($value)
    {
        return $this->builderHelper->getStyleProperty($value);
    }
}
