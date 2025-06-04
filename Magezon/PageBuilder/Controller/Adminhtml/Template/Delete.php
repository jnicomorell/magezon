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
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magezon\PageBuilder\Api\TemplateRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Controller for deleting a page builder template.
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Magezon_PageBuilder::template_delete';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

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
     * @param RequestInterface $request
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(
        Action\Context $context,
        RequestInterface $request,
        TemplateRepositoryInterface $templateRepository
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->messageManager = $context->getMessageManager();
        $this->templateRepository = $templateRepository;
    }

    /**
     * Delete action.
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $templateId = (int) $this->request->getParam('template_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($templateId) {
            try {
                $this->templateRepository->deleteById((string) $templateId);
                $this->messageManager->addSuccessMessage(__('The template has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The template no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['template_id' => $templateId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while deleting the template.'));
                return $resultRedirect->setPath('*/*/edit', ['template_id' => $templateId]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a template to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
