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

namespace Magezon\Builder\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magezon\Builder\Helper\Data as DataHelper;

class LibraryTemplate extends Action
{
    /**
     * @var DataHelper
     */
    protected $dataHelper;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * LibraryTemplate constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param DataHelper $dataHelper
     * @param RequestInterface $request
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        DataHelper $dataHelper,
        RequestInterface $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->dataHelper = $dataHelper;
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Execute controller action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = [];
        $post = $this->request->getPostValue();
        if (isset($post['url']) && $post['url']) {
            $result = $this->dataHelper->getTemplates($post['url']);
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
