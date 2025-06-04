<?php
declare(strict_types=1);

namespace Magezon\Core\Controller\Adminhtml\Wysiwyg\Images;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magezon\Core\Helper\Wysiwyg\Images as WysiwygHelper;
use Magento\Catalog\Helper\Data as CatalogHelper;

/**
 * Controller to handle insertion of WYSIWYG images.
 *
 * Note: Uses getRequest(), which is not an API method but required in controllers.
 */
class OnInsert extends Action
{
    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * @var WysiwygHelper
     */
    private $wysiwygHelper;

    /**
     * @var CatalogHelper
     */
    private $catalogHelper;

    /**
     * Constructor.
     */
    public function __construct(
        Context $context,
        RawFactory $resultRawFactory,
        WysiwygHelper $wysiwygHelper,
        CatalogHelper $catalogHelper
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->wysiwygHelper = $wysiwygHelper;
        $this->catalogHelper = $catalogHelper;
    }

    /**
     * Executes when an image is selected in WYSIWYG.
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        // Note: getRequest() is not API but used in controllers consistently.
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        $filename = (string)$this->getRequest()->getParam('filename', '');
        $asIs = (bool)$this->getRequest()->getParam('as_is', false);

        $filename = $this->wysiwygHelper->idDecode($filename);

        $this->catalogHelper->setStoreId($storeId);
        $this->wysiwygHelper->setStoreId($storeId);

        $imageHtml = $this->wysiwygHelper->getImageHtmlDeclaration($filename, $asIs);

        return $this->resultRawFactory->create()->setContents($imageHtml);
    }
}
