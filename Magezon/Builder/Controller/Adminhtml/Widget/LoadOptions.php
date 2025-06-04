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
use Magento\Framework\Controller\ResultInterface;

class LoadOptions extends Action
{
    const ADMIN_RESOURCE = 'Magezon_Builder::settings';

    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Constructor
     */
    public function __construct(
        Action\Context $context,
        LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Execute method
     *
     * @return ResultInterface
     */
    public function execute()
    {
        return $this->resultLayoutFactory->create();
    }
}