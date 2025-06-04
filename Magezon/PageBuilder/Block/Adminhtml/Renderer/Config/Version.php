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

namespace Magezon\PageBuilder\Block\Adminhtml\Renderer\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\ModuleListInterface;

/**
 * Renderer for displaying module version in system configuration.
 */
class Version extends Field
{
    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * Constructor.
     *
     * @param ModuleListInterface $moduleList
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ModuleListInterface $moduleList,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleList = $moduleList;
    }

    /**
     * Get module version to render in the system configuration.
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $moduleInfo = $this->moduleList->getOne('Magezon_PageBuilder');
        return $moduleInfo['setup_version'] ?? __('N/A');
    }
}
