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
use Magento\Framework\View\LayoutFactory;
use Magezon\Core\Helper\Data as CoreHelper;
use Magezon\Builder\Helper\Data as BuilderHelper;
use Magento\Framework\Controller\Result\JsonFactory;

class LoadStyles extends Action
{
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \Magezon\Builder\Helper\Data
     */
    protected $builderHelper;

    protected $request;
    protected $resultJsonFactory;


    /**
     * @param \Magento\Backend\App\Action\Context   $context       
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory 
     * @param \Magezon\Core\Helper\Data             $coreHelper    
     * @param \Magezon\Builder\Helper\Data          $builderHelper 
     */
    public function __construct(
        Action\Context $context,
        LayoutFactory $layoutFactory,
        CoreHelper $coreHelper,
        BuilderHelper $builderHelper,
        RequestInterface $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->layoutFactory = $layoutFactory;
        $this->coreHelper = $coreHelper;
        $this->builderHelper = $builderHelper;
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $result = [];
        try {
            $html = '';
            // Usar la instancia inyectada de RequestInterface
            $profile = str_replace(
                '"disable_element":true',
                '"disable_element":false',
                $this->request->getPost('profile')
            );
            $profile = $this->coreHelper->unserialize($profile);
            if (is_array($profile) && isset($profile['elements']) && is_array($profile['elements'])) {
                $block = $this->layoutFactory->create()->createBlock(\Magezon\Builder\Block\Profile::class);
                $block->setElements($profile['elements']);
                $html = $block->getStylesHtml();
            }
            if (isset($profile['custom_css'])) {
                $html .= '<style>' . $profile['custom_css'] . '</style>';
            }
            $html .= $this->builderHelper->getConfig('customization/css');
            $result['html'] = $html;
            $result['status'] = true;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
        } catch (\Exception $e) {
            $result['status'] = false;
            $result['message'] = __('Something went wrong while process preview styles.');
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the page.'));
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}