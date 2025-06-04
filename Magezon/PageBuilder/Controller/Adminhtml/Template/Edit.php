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

namespace Magezon\PageBuilder\Controller\Adminhtml\Template;

use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magezon\PageBuilder\Api\TemplateRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Edit template controller.
 */
class Edit extends Action
{
    const ADMIN_RESOURCE = 'Magezon_PageBuilder::template_save';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * Constructor.
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param RequestInterface $request
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        RequestInterface $request,
        TemplateRepositoryInterface $templateRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $request;
        $this->messageManager = $context->getMessageManager();
        $this->templateRepository = $templateRepository;
    }

    /**
     * Init actions.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Backend::content_elements')
            ->addBreadcrumb(__('Page Builder'), __('Page Builder'))
            ->addBreadcrumb(__('Manage Templates'), __('Manage Templates'));
        return $resultPage;
    }

    /**
     * Execute the edit action.
     *
     * @return \Magento\Backend\Model\View\Result\Page|Redirect
     */
    public function execute()
    {
        $id = (int) $this->request->getParam('template_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = null;

        if ($id) {
            try {
                $model = $this->templateRepository->getById((string) $id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('This template no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        }

        // Nota: aquí se debe pasar el modelo a la vista de forma alternativa a Registry
        // Por ejemplo, como parámetro de bloque en layout XML o usando un ViewModel

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Template') : __('New Template'),
            $id ? __('Edit Template') : __('New Template')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Templates'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model && $model->getId() ? $model->getName() : __('New Template'));

        return $resultPage;
    }
}
