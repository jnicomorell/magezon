<?php
/**
 * Magezon
 *
 * This source file is subject to la Magezon Software License, disponible en https://www.magezon.com/license.
 * No edites ni agregues contenido a este archivo si planeás actualizar el módulo en el futuro.
 *
 * @category  Magezon
 * @package   Magezon_PageBuilderPageableContainer
 * @author    quanth@magezon.com
 * @copyright Copyright (C) 2020 Magezon (https://www.magezon.com)
 */

namespace Magezon\PageBuilderPageableContainer\Block\Element;

use Magento\Framework\View\Element\Template\Context;
use Magezon\PageBuilderPageableContainer\ViewModel\PageableContainer as ViewModel;

class PageableContainer extends \Magezon\Builder\Block\Element
{
    /**
     * @var ViewModel
     */
    private ViewModel $viewModel;

    public function __construct(
        Context $context,
        ViewModel $viewModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->viewModel = $viewModel;
    }

    /**
     * Devuelve el ViewModel para usar en el template
     */
    public function getViewModel(): ViewModel
    {
        return $this->viewModel;
    }

    /**
     * Genera estilos adicionales para el componente
     *
     * @return string
     */
    public function getAdditionalStyleHtml(): string
    {
        $styleHtml         = '';
        $element           = $this->getElement();
        $id                = $element->getId();
        $titleSelector     = '.mgz-tabs-' . $id . ' > .mgz-tabs-nav > .mgz-tabs-tab-title';
        $titleSelector2    = '.mgz-tabs-' . $id . ' > .mgz-tabs-content > .mgz-tabs-tab-title';
        $contentSelector   = '.mgz-tabs-' . $id . ' > .mgz-tabs-content > .mgz-tabs-tab-content';
        $noFillContentArea = $element->getData('no_fill_content_area');
        $tabPosition       = $element->getData('tab_position');
        $styleHtml         = $this->getOwlCarouselStyles();

        // DOTS
        $styles = [];
        $styles['background'] = $this->getStyleColor($element->getData('owl_background_color'));
        $styleHtml .= $this->getStyles([
            $titleSelector . ' > a',
            $titleSelector2 . ' > a'
        ], $styles);

        // DOTS HOVER
        $styles = [];
        $styles['background'] = $this->getStyleColor($element->getData('owl_hover_background_color'));
        $styleHtml .= $this->getStyles([
            $titleSelector . ' > a'
        ], $styles, ':hover');

        // DOTS ACTIVE
        $styles = [];
        $styles['background'] = $this->getStyleColor($element->getData('owl_active_background_color'));
        $styleHtml .= $this->getStyles([
            $titleSelector . '.mgz-active > a',
            $titleSelector2 . '.mgz-active > a'
        ], $styles);

        if ($element->getData('owl_dots_insie') == true) {
            // DOTS INSIDE
            $styles = [];
            $styles['background'] = '#ffffff';
            $styles['box-shadow'] = '0 1px 2px rgba(0, 0, 0, 0.3)';
            $styles['margin']     = '8px !important';
            $styleHtml .= $this->getStyles([
                $titleSelector . ' > a',
                $titleSelector2 . ' > a'
            ], $styles);

            // DOTS INSIDE HOVER
            $styles = [];
            $styles['width']  = '16px';
            $styles['height'] = '16px';
            $styles['margin'] = '5px !important';
            $styleHtml .= $this->getStyles([
                $titleSelector . ' > a',
                $titleSelector2 . ' > a'
            ], $styles, ':hover');

            // DOTS INSIDE ACTIVE
            $styles = [];
            $styles['width']  = '16px';
            $styles['height'] = '16px';
            $styles['margin'] = '5px !important';
            $styleHtml .= $this->getStyles([
                $titleSelector . '.mgz-active > a',
                $titleSelector2 . '.mgz-active > a'
            ], $styles);
        }

        return $styleHtml;
    }
}
