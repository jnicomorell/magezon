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
use Magezon\Builder\Data\SourcesFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class ItemList extends Action
{
    /**
     * @var SourcesFactory
     */
    protected $sourcesFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param SourcesFactory $sourcesFactory
     * @param RequestInterface $request
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        SourcesFactory $sourcesFactory,
        RequestInterface $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->sourcesFactory = $sourcesFactory;
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $data = [];
        try {
            $post = $this->request->getPostValue();
            if (isset($post['type']) && $post['type']) {
                $sources = $this->sourcesFactory->create();
                $source  = $sources->getSource($post['type']);
                $field   = isset($post['field']) ? $post['field'] : '';
                if ($source) {
                    $q = isset($post['q']) ? $post['q'] : '';
                    $data = $source->getList($q, $field);
                }
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while processing the request.'));
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($data);
    }
}
