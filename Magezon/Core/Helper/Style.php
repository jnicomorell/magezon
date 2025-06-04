<?php
declare(strict_types=1);

namespace Magezon\Core\Helper;

/**
 * Style helper to build CSS styles dynamically.
 */
class Style
{
    /**
     * @var Data
     */
    protected Data $dataHelper;

    public function __construct(Data $dataHelper)
    {
        $this->dataHelper = $dataHelper;
    }

    /**
     * Build CSS rule string.
     */
    public function getHtml($target, array $styles, string $suffix = ''): ?string
    {
        $html = '';
        if (is_array($target)) {
            $target = array_filter($target);
            $html = implode(',', array_map(fn($sel) => $sel . $suffix, $target));
        } else {
            $html = $target . $suffix;
        }

        $stylesHtml = $this->parseStyles($styles);
        if (!$stylesHtml) {
            return null;
        }

        return $html . '{' . $stylesHtml . '}';
    }

    /**
     * Convert array to CSS style string.
     */
    public function parseStyles(array $styles): string
    {
        $result = '';
        foreach ($styles as $k => $v) {
            if ($v !== '') {
                $result .= $k . ':' . $v . ';';
            }
        }
        return $result;
    }

    /**
     * Normalize color value.
     */
    public function getColor(string $value, bool $important = false): string
    {
        if ($value && !$this->dataHelper->startsWith($value, '#') && !$this->dataHelper->startsWith($value, 'rgb') && $value !== 'transparent') {
            $value = '#' . $value;
        }
        return $value && $important ? $value . ' !important' : $value;
    }

    /**
     * Normalize numeric property.
     */
    public function getProperty($value, bool $important = false, string $unit = 'px'): string
    {
        if (is_numeric($value)) {
            $value .= $unit;
        } elseif ($value === '-') {
            $value = '';
        }
        return $value && $important ? $value . ' !important' : $value;
    }

    /**
     * Check if value is considered null.
     */
    public function isNull($value): bool
    {
        return !is_numeric($value) && ($value === '' || $value === null);
    }

    /**
     * Generate array of CSS styles.
     */
    public function getStyles(array $data): array
    {
        $styles = [];
        $config = new \Magento\Framework\DataObject($data);

        // Padding
        $padding = array_map(fn($key) => $this->getProperty($config->getData($key)), [
            'padding_top', 'padding_right', 'padding_bottom', 'padding_left'
        ]);
        $styles['padding'] = implode(' ', $padding);

        // Margin
        $margin = array_map(fn($key) => $this->getProperty($config->getData($key)), [
            'margin_top', 'margin_right', 'margin_bottom', 'margin_left'
        ]);
        $styles['margin'] = implode(' ', $margin);

        // Border
        $borderStyle = $config->getData('border_style');
        $borderColor = $this->getColor($config->getData('border_color'));
        if ($borderStyle && $borderColor) {
            $widths = array_map(fn($key) => $this->getProperty($config->getData($key)), [
                'border_top_width', 'border_right_width', 'border_bottom_width', 'border_left_width'
            ]);
            if (count(array_unique($widths)) === 1) {
                $styles['border'] = "{$widths[0]} {$borderStyle} {$borderColor}";
            } else {
                $styles = array_merge($styles, [
                    'border-top' => "{$widths[0]} {$borderStyle} {$borderColor}",
                    'border-right' => "{$widths[1]} {$borderStyle} {$borderColor}",
                    'border-bottom' => "{$widths[2]} {$borderStyle} {$borderColor}",
                    'border-left' => "{$widths[3]} {$borderStyle} {$borderColor}",
                ]);
            }
        }

        // Border radius
        $radius = array_map(fn($key) => $this->getProperty($config->getData($key)), [
            'border_top_left_radius', 'border_top_right_radius', 'border_bottom_right_radius', 'border_bottom_left_radius'
        ]);
        if (count(array_unique($radius)) === 1) {
            $styles['border-radius'] = $radius[0];
        } else {
            $styles['border-radius'] = implode(' ', $radius);
        }

        // Background
        $styles['background-color'] = $this->getColor($config->getData('background_color'));
        if ($bgImage = $config->getData('background_image')) {
            $styles['background-image'] = "url('{$this->dataHelper->getImageUrl($bgImage)}')";
            $styles['background-position'] = $config->getData('background_position') === 'custom'
                ? $config->getData('custom_background_position')
                : $config->getData('background_position');
            $styles['background-size'] = $config->getData('background_size') === 'custom'
                ? $config->getData('custom_background_size')
                : $config->getData('background_size');
            $styles['background-repeat'] = $config->getData('background_repeat');
        }

        // Box shadow
        if ($config->getData('boxshadow')) {
            $shadow = array_map(fn($key) => $this->getProperty($config->getData($key)), [
                'boxshadow_horizontal', 'boxshadow_vertical', 'boxshadow_blur', 'boxshadow_spread'
            ]);
            $color = $this->getColor($config->getData('boxshadow_color'));
            $styles['box-shadow'] = implode(' ', $shadow) . ' ' . $color;
            if ($config->getData('boxshadow_position') === 'inset') {
                $styles['box-shadow'] .= ' inset';
            }
        }

        return array_filter($styles);
    }
}
