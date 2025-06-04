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
use Magento\Framework\View\LayoutFactory;
use Magento\CatalogWidget\Model\RuleFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Conditions extends Action
{
    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

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
     * @param LayoutFactory $layoutFactory
     * @param RuleFactory $ruleFactory
     * @param RequestInterface $request
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        LayoutFactory $layoutFactory,
        RuleFactory $ruleFactory,
        RequestInterface $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->layoutFactory     = $layoutFactory;
        $this->ruleFactory       = $ruleFactory;
        $this->request           = $request;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $result = ['status' => false];
        try {
            $post = $this->request->getPostValue();
            $data = [];
            if (isset($post['conditions'])) {
                $data['conditions'] = $post['conditions'];
            }
            $result['html']   = $this->getConditions($data, isset($post['id']) ? $post['id'] : '');
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

    /**
     * @param  array  $parameters
     * @param  string $htmlId
     * @return string
     */
    public function getConditions($parameters, $htmlId)
    {
        $block = $this->layoutFactory->create()->createBlock(
            \Magezon\Core\Block\Adminhtml\Product\Widget\Conditions::class
        );
        $block->setTemplate('Magezon_Builder::product/widget/conditions.phtml');
        $block->setData('parameters', $parameters);
        $block->setData('htmlid', $htmlId);
        $rule = $this->ruleFactory->create();
        $block->setRule($rule);
        return $block->toHtml();
    }
}
