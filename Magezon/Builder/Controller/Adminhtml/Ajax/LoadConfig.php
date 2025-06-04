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
use Magento\Framework\Stdlib\ArrayManager;
use Magezon\Builder\Model\CompositeConfigProvider;
use Magezon\Builder\Model\CacheManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class LoadConfig extends Action
{
    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var CompositeConfigProvider
     */
    protected $configProvider;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * LoadConfig constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param LayoutFactory $layoutFactory
     * @param ArrayManager $arrayManager
     * @param CompositeConfigProvider $configProvider
     * @param CacheManager $cacheManager
     * @param RequestInterface $request
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        LayoutFactory $layoutFactory,
        ArrayManager $arrayManager,
        CompositeConfigProvider $configProvider,
        CacheManager $cacheManager,
        RequestInterface $request,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->_backendUrl      = $context->getBackendUrl();
        $this->layoutFactory    = $layoutFactory;
        $this->arrayManager     = $arrayManager;
        $this->configProvider   = $configProvider;
        $this->cacheManager     = $cacheManager;
        $this->request          = $request;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Execute controller action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = ['status' => false];
        try {
            $class = $this->request->getPost('class');
            $key   = $this->request->getPost('key');
            $area  = $this->request->getPost('area');
            if ($cacheData = $this->cacheManager->getFromCache($key . $area)) {
                $path = $this->arrayManager->findPath('files_browser_window_url', $cacheData, null);
                if ($this->_backendUrl->useSecretKey() && !empty($path)) {
                    $filesBrowserWindowUrl = $this->arrayManager->get($path, $cacheData);
                    $pos    = strpos($filesBrowserWindowUrl, '/key/');
                    $oldKey = substr($filesBrowserWindowUrl, $pos + 5);
                    $filesBrowserWindowUrl = str_replace(
                        $oldKey,
                        $this->_backendUrl->getSecretKey('cms', 'wysiwyg_images', 'index') . '/',
                        $filesBrowserWindowUrl
                    );
                    $path = str_replace('/files_browser_window_url', '', $path);
                    $cacheData = $this->arrayManager->merge(
                        $path,
                        $cacheData,
                        [
                            'files_browser_window_url' => $filesBrowserWindowUrl
                        ]
                    );
                }
                $result = $cacheData;
            } else {
                if ($class) {
                    $block  = $this->layoutFactory->create()->createBlock($class);
                    $config = $block->getBuilderConfig();
                } else {
                    $config = $this->configProvider->getConfig();
                }
                $row = $this->arrayManager->get($key, $config, [], '.');
                if (isset($row['class'])) {
                    $obj = $this->_objectManager->create($row['class']);
                    if (method_exists($obj, 'addData')) {
                        $obj->addData($row);
                    }
                    if (method_exists($obj, 'getConfig')) {
                        $result['config'] = $obj->getConfig();
                    } elseif (method_exists($obj, 'getOptions')) {
                        $result['config'] = $obj->getOptions();
                    }
                }
                if ((isset($row['cache']) && $row['cache']) || !isset($row['cache'])) {
                    $this->cacheManager->saveToCache($key . $area, $result);
                }
            }
            $result['status'] = true;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $result['message'] = $e->getMessage();
            $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
        } catch (\Exception $e) {
            $result['message'] = __('Something went wrong while process the request.');
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while processing the request.'));
        }

        /** Usar JsonFactory para la respuesta */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($result);
    }
}
