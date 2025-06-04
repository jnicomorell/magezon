<?php
declare(strict_types=1);

namespace Magezon\Core\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Heading field block for system configuration.
 * Displays a heading separator row in admin system config.
 */
class Heading extends Field
{
    /**
     * Render heading row for system config.
     *
     * Note: $element->getLabel() is a magic method inherited from DataObject.
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $html = '<tr id="row_' . $element->getHtmlId() . '">'
            . '<td class="label" colspan="3">'
            . '<div class="mgz-system-heading">'
            . $element->getLabel()
            . '</div></td></tr>';
        return $html;
    }
}
