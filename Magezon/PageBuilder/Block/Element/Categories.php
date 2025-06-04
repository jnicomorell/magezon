<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs, please refer to https://www.magezon.com for more information.
 *
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

declare(strict_types=1);

namespace Magezon\PageBuilder\Block\Element;

use Magento\Catalog\Model\Layer\Resolver as LayerResolver;

/**
 * Categories block for displaying category listings.
 */
class Categories extends \Magezon\Builder\Block\Element
{
    private \Magezon\PageBuilder\ViewModel\CategoriesData $viewModel;
    /**
     * @var \Magezon\PageBuilder\Model\Source\Categories
     */
    protected $categories;

    /**
     * @var LayerResolver
     */
    private $layerResolver;

    /**
     * @var array
     */
    protected $_categories;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magezon\Core\Model\Source\Categories $categories
     * @param LayerResolver $layerResolver
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magezon\Core\Model\Source\Categories $categories,
        LayerResolver $layerResolver,
        \Magezon\PageBuilder\ViewModel\CategoriesData $viewModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categories = $categories;
        $this->layerResolver = $layerResolver;
        $this->viewModel = $viewModel;
    }

    public function getViewModel(): \Magezon\PageBuilder\ViewModel\CategoriesData
    {
        return $this->viewModel;
    }

    /**
     * Check if block is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        if (!$this->getElement()->getData('categories')) {
            return false;
        }
        return parent::isEnabled();
    }

    /**
     * Get categories data.
     *
     * @return array
     */
    public function getCategories(): array
    {
        if ($this->_categories === null) {
            $element = $this->getElement();
            $this->_categories = $this->categories->getCategories(
                $element->getData('categories'),
                $element->getData('show_count')
            );
        }
        return $this->_categories;
    }

    /**
     * Render categories HTML recursively.
     *
     * @param array $categories
     * @param int $level
     * @return string
     */
    public function getCategoriesHtml(array $categories, int $level = 0): string
    {
        $element = $this->getElement();
        $showCount = $element->getData('show_count');
        $showHierarchical = $element->getData('show_hierarchical');

        $html = '<ul class="mgz-categories-level' . $level . '">';
        foreach ($categories as $category) {
            $children = $category->getSubCategories();
            $classes = [];
            if ($this->isActive($category)) {
                $classes[] = 'active';
            }
            $_class = $classes ? 'class="' . implode(' ', $classes) . '"' : '';
            $html .= '<li ' . $_class . '>';
            $html .= '<a href="' . $category->getUrl() . '">';
            $html .= '<span>' . $category->getName() . '</span>';
            if ($showCount) {
                $html .= '<span>(' . $category->getProductCount() . ')</span>';
            }
            if ($showHierarchical && $children) {
                $html .= '<span class="opener"></span>';
            }
            $html .= '</a>';
            if ($showHierarchical && $children) {
                $html .= $this->getCategoriesHtml($children, $level + 1);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Retrieve current category.
     *
     * @return \Magento\Catalog\Model\Category|null
     */
    public function getCurrentCategory(): ?\Magento\Catalog\Model\Category
    {
        $layer = $this->layerResolver->get();
        return $layer->getCurrentCategory();
    }

    /**
     * Check if given category is active.
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return bool
     */
    public function isActive($category): bool
    {
        $currentCategory = $this->getCurrentCategory();
        return $currentCategory && $currentCategory->getId() == $category->getId();
    }

    /**
     * Get additional styles for the element.
     *
     * @return string
     */
    public function getAdditionalStyleHtml(): string
    {
        $styleHtml = parent::getAdditionalStyleHtml();
        $element = $this->getElement();

        if ($element->getData('categories')) {
            $styles = [];
            $styles['color'] = $this->getStyleColor($element->getData('link_color'));
            $styles['font-size'] = $this->getStyleProperty($element->getData('link_font_size'));
            $styles['font-weight'] = $element->getData('link_font_weight');
            $styleHtml .= $this->getStyles('.mgz-element-categories-list a', $styles);

            $styles = [];
            $styles['color'] = $this->getStyleColor($element->getData('link_hover_color'));
            $styleHtml .= $this->getStyles(
                ['.mgz-element-categories-list a:hover', '.mgz-element-categories-list li.active > a'],
                $styles,
                ''
            );

            $styles = [];
            $styles['border-bottom-width'] = $this->getStyleProperty($element->getData('link_border_width'));
            $styles['border-bottom-color'] = $this->getStyleColor($element->getData('link_border_color'));
            $styleHtml .= $this->getStyles('.mgz-element-categories-list li', $styles);
        }

        $styleHtml .= $this->getLineStyles();

        return $styleHtml;
    }
}
