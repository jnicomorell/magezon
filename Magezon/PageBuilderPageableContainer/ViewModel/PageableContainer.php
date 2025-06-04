<?php

namespace Magezon\PageBuilderPageableContainer\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;

class PageableContainer implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper
    ) {}

    public function getSerializedOptions(array $options): string
    {
        return $this->coreHelper->serialize($options);
    }
}
