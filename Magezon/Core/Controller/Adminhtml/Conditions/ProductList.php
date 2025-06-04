<?php
declare(strict_types=1);

namespace Magezon\Core\Controller\Adminhtml\Conditions;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;
use Magezon\Core\Block\Adminhtml\Conditions\Product;
use Magento\CatalogRule\Model\Rule;

/**
 * Controller for rendering the product grid based on conditions.
 *
 * Note: Uses non-API classes and methods (Rule::loadPost()), recommended to replace in the future.
 */
class ProductList extends Action
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
     * @var Product
     */
    private $gridProduct;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param RawFactory $resultRawFactory
     * @param LayoutFactory $layoutFactory
     * @param DataPersistorInterface $dataPersistor
     * @param Product $gridProduct
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        LayoutFactory $layoutFactory,
        DataPersistorInterface $dataPersistor,
        Product $gridProduct
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->dataPersistor = $dataPersistor;
        $this->gridProduct = $gridProduct;
    }

    /**
     * Execute action to render the product grid.
     *
     * @return Raw
     */
    public function execute(): Raw
    {
        // Using getRequest() is discouraged in templates but acceptable in controllers
        $data = (array) $this->getRequest()->getParams();

        // Process conditions
        if (isset($data['rule'])) {
            $data['conditions'] = $data['rule']['conditions'];
            unset($data['rule']);
        }

        unset($data['conditions_serialized'], $data['actions_serialized']);

        // Using non-API class Rule and loadPost()
        /** @var Rule $model */
        $model = $this->_objectManager->create(Rule::class);
        $model->loadPost($data);

        // Store the model using DataPersistor instead of deprecated Registry
        $this->dataPersistor->set('mgz_conditions_model', $model);

        // Create and render grid HTML
        $resultRaw = $this->resultRawFactory->create();
        $gridHtml = $this->layoutFactory->create()->createBlock(
            Product::class,
            'product.grid'
        )->toHtml();

        return $resultRaw->setContents($gridHtml);
    }
}
