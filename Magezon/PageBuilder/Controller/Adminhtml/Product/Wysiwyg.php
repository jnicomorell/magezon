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

namespace Magezon\PageBuilder\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Controller for WYSIWYG editor in PageBuilder.
 */
class Wysiwyg extends Action
{
    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Constructor.
     *
     * @param Action\Context $context
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Action\Context $context,
        RawFactory $resultRawFactory,
        LayoutFactory $layoutFactory,
        RequestInterface $request,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * WYSIWYG editor action for AJAX request.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $elementId = (string) $this->request->getParam('element_id', hash('sha256', microtime()));
        $storeId = (int) $this->request->getParam('store_id', 0);
        $storeMediaUrl = $this->storeManager
            ->getStore($storeId)
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $content = $this->layoutFactory->create()
            ->createBlock(
                \Magezon\PageBuilder\Block\Adminhtml\Helper\Form\Wysiwyg\Content::class,
                '',
                [
                    'data' => [
                        'editor_element_id' => $elementId,
                        'store_id' => $storeId,
                        'store_media_url' => $storeMediaUrl
                    ]
                ]
            );

        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($content->toHtml());
    }
}
