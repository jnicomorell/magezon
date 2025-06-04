<?php
declare(strict_types=1);

namespace Magezon\PageBuilder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\PageBuilder\Block\Element\Categories as CategoriesBlock;

class CategoriesData implements ArgumentInterface
{
    public function __construct(
        private CoreHelper $coreHelper,
        private CategoriesBlock $block
    ) {}

    public function getElement()
    {
        return $this->block->getElement();
    }

    public function filter(?string $text): string
    {
        return $this->coreHelper->filter($text ?? '');
    }

    public function getCategories()
    {
        return $this->block->getCategories();
    }

    public function getCategoriesHtml(array $categories): string
    {
        return $this->block->getCategoriesHtml($categories);
    }
}
