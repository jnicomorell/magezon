<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_Builder
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

namespace Magezon\Builder\Ui\Component\Form\Element;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Ui\Component\Form\Element\AbstractElement;
use Magento\Ui\Component\Wysiwyg\ConfigInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\Registry;

class Builder extends AbstractElement
{
    const NAME = 'wysiwyg';

    protected $formFactory;
    protected $layoutFactory;

    public function __construct(
        ContextInterface $context,
        FormFactory $formFactory,
        ConfigInterface $wysiwygConfig,
        LayoutFactory $layoutFactory,
        Registry $registry,
        array $components = [],
        array $data = [],
        array $config = []
    ) {
        $this->formFactory = $formFactory;
        $this->layoutFactory = $layoutFactory;

        if (!isset($config['disableMagezonBuilder']) || !$config['disableMagezonBuilder']) {
            $htmlId                        = $context->getNamespace() . '_' . $data['name'];
            $data['config']['htmlId']      = $htmlId;
            $data['config']['component']   = 'Magezon_Builder/js/ui/form/element/builder';
            $data['config']['elementTmpl'] = 'Magezon_Builder/ui/form/element/builder';
            $data['config']['template']    = 'ui/form/field';

            $block = $layoutFactory->create()->createBlock(\Magento\Backend\Block\Template::class)
                ->addData($config)
                ->setTemplate('Magezon_Builder::ajax.phtml')
                ->setTargetId($htmlId);

            if (isset($config['ajax_data'])) {
                $block->setAjaxData($config['ajax_data']);
                $data['config']['content'] = $block->toHtml();
            }
            } else {
                $wysiwygConfigData = isset($config['wysiwygConfigData']) ? $config['wysiwygConfigData'] : [];
                $htmlId = $context->getNamespace() . '_' . $data['name'];

                $data['config']['component']   = 'Magento_Ui/js/form/element/wysiwyg';
                $data['config']['elementTmpl'] = 'ui/form/element/wysiwyg';
                $data['config']['template']    = 'ui/form/field';
                $data['config']['config']      = $wysiwygConfig->getConfig($wysiwygConfigData);
                $data['config']['wysiwyg']     = $config['wysiwyg'] ?? true;
                $data['config']['rows']        = $config['rows'] ?? 20;
                $data['config']['htmlId']      = $htmlId;
                $data['config']['wysiwygId']   = $htmlId;
            }

        parent::__construct($context, $components, $data);
    }

    public function getComponentName()
    {
        return static::NAME;
    }
}
