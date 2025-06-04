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

namespace Magezon\PageBuilder\Block\Adminhtml\Helper\Form\Wysiwyg;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;

/**
 * Block for WYSIWYG content editor form.
 */
class Content extends Template
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var WysiwygConfig
     */
    private $wysiwygConfig;

    /**
     * Constructor
     *
     * @param Context $context
     * @param FormFactory $formFactory
     * @param WysiwygConfig $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        FormFactory $formFactory,
        WysiwygConfig $wysiwygConfig,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get prepared form HTML.
     *
     * @return string
     */
    public function getFormHtml(): string
    {
        $form = $this->formFactory->create(
            [
                'data' => [
                    'id' => 'wysiwyg_edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post'
                ],
            ]
        );

        $config = [
            'document_base_url' => $this->getData('store_media_url'),
            'store_id' => $this->getData('store_id'),
            'add_variables' => true,
            'add_widgets' => true,
            'add_directives' => true,
            'use_container' => true,
            'container_class' => 'hor-scroll'
        ];

        $form->addField(
            $this->getData('editor_element_id'),
            'editor',
            [
                'name' => 'content',
                'style' => 'width:725px;height:460px',
                'required' => true,
                'force_load' => true,
                'config' => $this->wysiwygConfig->getConfig($config)
            ]
        );

        return $form->toHtml();
    }
}
