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

class ConditionsValue extends Action
{
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
     * @param RequestInterface $request
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        RequestInterface $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $result = ['status' => false];

        try {
            $post = $this->request->getPostValue();
            $options = [];
            if (isset($post['values'])) {
                $queryString = $post['values'];
                $pairs = explode('&', $queryString);
                foreach ($pairs as $pair) {
                    $parts = explode('=', $pair, 2);
                    $key = urldecode($parts[0]);
                    $value = isset($parts[1]) ? urldecode($parts[1]) : '';
                    if (preg_match('/^([^\[]+)\[([^\]]+)\]$/', $key, $matches)) {
                        $options[$matches[1]][$matches[2]] = $value;
                    } else {
                        $options[$key] = $value;
                    }
                }
            }

            $result['value'] = isset($options['parameters']['conditions'])
                ? json_encode($options['parameters']['conditions'])
                : '';
            $result['status'] = true;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $result['message'] = $e->getMessage();
            $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
        } catch (\Exception $e) {
            $result['message'] = __('Something went wrong while processing the request.');
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while processing the request.'));
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
