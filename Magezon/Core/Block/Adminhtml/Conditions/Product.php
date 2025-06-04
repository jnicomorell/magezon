<?php
declare(strict_types=1);

namespace Magezon\Core\Block\Adminhtml\Conditions;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Container;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResourceConnection;
use Magezon\Core\Model\ConditionsProcessor;

/**
 * Product block for conditions product list.
 * Displays and manages the product IDs based on conditions.
 */
class Product extends Container
{
    /**
     * Cache group identifier.
     *
     * @var string
     */
    public const CACHE_GROUP = \Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER;

    /**
     * Cache tag.
     *
     * @var string
     */
    public const CACHE_TAG = \Magento\Framework\App\Cache\Type\Config::CACHE_TAG;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $systemStore;

    /**
     * @var ConditionsProcessor
     */
    private $processor;

    /**
     * @var array|null
     */
    private $_cacheManager;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    private $coreHelper;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var Visibility
     */
    private $visibility;

    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var StateInterface
     */
    private $cacheState;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Product constructor.
     *
     * @param Context $context
     * @param Status $status
     * @param Visibility $visibility
     * @param ProductFactory $productFactory
     * @param ResourceConnection $resource
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param StateInterface $cacheState
     * @param \Magezon\Core\Helper\Data $coreHelper
     * @param ConditionsProcessor $processor
     * @param DataPersistorInterface $dataPersistor
     * @param array $data
     */
    public function __construct(
        Context $context,
        Status $status,
        Visibility $visibility,
        ProductFactory $productFactory,
        ResourceConnection $resource,
        \Magento\Store\Model\System\Store $systemStore,
        StateInterface $cacheState,
        \Magezon\Core\Helper\Data $coreHelper,
        ConditionsProcessor $processor,
        DataPersistorInterface $dataPersistor,
        array $data = []
    ) {
        $this->resource = $resource;
        $this->status = $status;
        $this->visibility = $visibility;
        $this->productFactory = $productFactory;
        $this->systemStore = $systemStore;
        $this->cacheState = $cacheState;
        $this->coreHelper = $coreHelper;
        $this->processor = $processor;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve product IDs based on conditions.
     *
     * @return array
     */
    public function getProductIds(): array
    {
        $model = $this->getModel();
        $formName = $this->dataPersistor->get('mgz_conditions_form_name') ?? '';
        if ($formName !== 'mgz_landing_page_form') {
            $productIds = $this->getFromCache();
            if (!empty($productIds)) {
                return $productIds;
            }
        }

        $storeIds = $model->getStoreId();
        $productIds = [];

        if (!empty($storeIds)) {
            if (in_array(0, $storeIds, true)) {
                $stores = $this->systemStore->getStoreValuesForForm();
                foreach ($stores as $store) {
                    if (is_array($store['value']) && !empty($store['value'])) {
                        foreach ($store['value'] as $_store) {
                            $storeModel = $this->_storeManager->getStore($_store['value']);
                            $ids = $this->processor->getProductByConditions($model, $storeModel)->getAllIds();
                            $productIds = array_merge($productIds, $ids);
                        }
                    }
                }
            } else {
                foreach ($storeIds as $storeId) {
                    $store = $this->_storeManager->getStore($storeId);
                    $ids = $this->processor->getProductByConditions($model, $store)->getAllIds();
                    $productIds = array_merge($productIds, $ids);
                }
            }
        }

        if ($formName !== 'mgz_landing_page_form') {
            $this->saveToCache($productIds);
        }

        return $productIds;
    }

    /**
     * Retrieve the current conditions model from DataPersistor.
     *
     * @return mixed|null
     */
    public function getModel()
    {
        return $this->dataPersistor->get('mgz_conditions_model');
    }

    /**
     * Get product IDs from cache.
     *
     * @return array|null
     */
    public function getFromCache()
    {
        if (!$this->cacheState->isEnabled(self::CACHE_GROUP)) {
            return null;
        }
        $key = $this->getGridCacheKey();
        $config = $this->getCacheManager()->load($key);
        if ($config) {
            return $this->coreHelper->unserialize($config);
        }
        return null;
    }

    /**
     * Save product IDs to cache.
     *
     * @param array $value
     * @return void
     */
    public function saveToCache(array $value): void
    {
        if ($this->cacheState->isEnabled(self::CACHE_GROUP)) {
            $key = $this->getGridCacheKey();
            $this->getCacheManager()->save(
                $this->coreHelper->serialize($value),
                $key,
                [self::CACHE_TAG]
            );
        }
    }

    /**
     * Retrieve the cache interface instance.
     *
     * @return CacheInterface
     */
    private function getCacheManager(): CacheInterface
    {
        if (!$this->_cacheManager) {
            $this->_cacheManager = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(CacheInterface::class);
        }
        return $this->_cacheManager;
    }

    /**
     * Generate unique grid cache key.
     *
     * @return string
     */
    public function getGridCacheKey(): string
    {
        return (string)$this->getRequest()->getParam('mgz_conditions_grid_id');
    }
}
