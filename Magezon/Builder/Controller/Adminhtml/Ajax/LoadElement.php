<?php
namespace Magezon\Builder\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Store\Model\App\Emulation;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Registry;
use Magezon\Builder\Data\Elements;
use Magezon\Core\Helper\Data as CoreHelper;

class LoadElement extends Action
{
    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var Emulation
     */
    protected $_appEmulation;

    /**
     * @var State
     */
    protected $_appState;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Elements
     */
    protected $elements;

    /**
     * @var CoreHelper
     */
    protected $coreHelper;

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(
        Action\Context $context,
        RawFactory $resultRawFactory,
        Emulation $appEmulation,
        State $appState,
        StoreManagerInterface $storeManager,
        Registry $registry,
        Elements $elements,
        CoreHelper $coreHelper,
        RequestInterface $request
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->_appEmulation    = $appEmulation;
        $this->_appState        = $appState;
        $this->_storeManager    = $storeManager;
        $this->registry         = $registry;
        $this->elements         = $elements;
        $this->coreHelper       = $coreHelper;
        $this->request          = $request;
    }

    public function execute()
    {
        $this->registry->register('magezon_builder_loadelement', true);
        $content = '';

        $post = $this->request->getPostValue();

        $data = $this->coreHelper->unserialize($post['element']);
        unset($data['component'], $data['$$hashKey']);

        foreach ($data as $key => &$value) {
            if ($value === 'true') $value = 1;
            if ($value === 'false') $value = 0;
        }

        $element = $this->elements->getElementModel($data);
        $element->setEnableCache(false);
        $storeId = $this->_storeManager->getDefaultStoreView()->getId();
        $store   = $this->request->getParam('store_id', $this->_storeManager->getStore($storeId));
        $this->_appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
        if ($block = $element->getElementBlock()) {
            $block->setStore($store);
            $content = $this->_appState->emulateAreaCode(
                \Magento\Framework\App\Area::AREA_FRONTEND,
                [$block, 'toHtml']
            );
        }
        $this->_appEmulation->stopEnvironmentEmulation();

        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($content);
    }
}
