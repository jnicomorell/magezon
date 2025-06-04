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

/**
 * Block for rendering the contact form element.
 */
class ContactForm extends \Magezon\Builder\Block\Element
{
    /**
     * Get contact form HTML.
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getContactFormHtml(): string
    {
        $contactForm = $this->getLayout()->createBlock(
            \Magento\Contact\Block\ContactForm::class,
            'contactForm'
        )->setTemplate('Magento_Contact::form.phtml');

        return $contactForm->toHtml();
    }

    /**
     * Get additional styles for the contact form.
     *
     * @return string
     */
    public function getAdditionalStyleHtml(): string
    {
        $styleHtml = '';
        $element = $this->getElement();

        $styles = [];
        $styles['width'] = $this->getStyleProperty($element->getData('form_width'), true);
        $styleHtml .= $this->getStyles('.form.contact', $styles);

        if (!$element->getData('show_title')) {
            $styles = [];
            $styles['display'] = 'none';
            $styleHtml .= $this->getStyles('.form.contact .legend', $styles);
        }

        if (!$element->getData('show_description')) {
            $styles = [];
            $styles['display'] = 'none';
            $styleHtml .= $this->getStyles('.field.note', $styles);
        }

        return $styleHtml;
    }
}
