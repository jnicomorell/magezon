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

namespace Magezon\Builder\Controller\Adminhtml\Widget;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\Element\Template;

class BuildWidget extends Action
{
    const ADMIN_RESOURCE = 'Magezon_Builder::settings';

    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * Constructor
     */
    public function __construct(
        Action\Context $context,
        LayoutFactory $resultLayoutFactory,
        RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultRawFactory = $resultRawFactory;
    }

    /**
     * Render widget output
     */
    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        /** @var Template $block */
        $block = $resultLayout->getLayout()->createBlock(
            \Magento\Widget\Block\Adminhtml\Widget::class
        )->setTemplate('Magento_Widget::widget/build_widget.phtml');

        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($block->toHtml());
        return $resultRaw;
    }
}
