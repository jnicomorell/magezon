<?php
declare(strict_types=1);

namespace Magezon\Core\Block\Adminhtml\Widget\Field;

use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;

/**
 * Heading renderer for admin widget fields.
 */
class Heading extends Template implements RendererInterface
{
    /**
     * Render the heading label for the field.
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        // getLabel() is a magic method from DataObject.
        $html = '<div class="mgz-heading">' . $element->getLabel() . '</div>';
        return $html;
    }
}
