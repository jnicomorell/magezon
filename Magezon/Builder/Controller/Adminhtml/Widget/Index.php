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

class Index extends Action
{
    const ADMIN_RESOURCE = 'Magezon_Builder::settings';

    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Action\Context $context,
        LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        return $this->resultLayoutFactory->create();
    }
}
