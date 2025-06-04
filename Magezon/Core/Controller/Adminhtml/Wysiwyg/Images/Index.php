<?php
declare(strict_types=1);

namespace Magezon\Core\Controller\Adminhtml\Wysiwyg\Images;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\LayoutFactory;

/**
 * Reimplemented controller for WYSIWYG images overlay popup.
 */
class Index extends Action
{
    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Index action to render the overlay popup for WYSIWYG images.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);

        /** @var \Magento\Framework\View\Result\Layout $resultLayout */
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->addHandle('overlay_popup');

        $block = $resultLayout->getLayout()->getBlock('wysiwyg_images.js');
        if ($block) {
            $block->setStoreId($storeId);
        }

        return $resultLayout;
    }
}
