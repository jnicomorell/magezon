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
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magezon\PageBuilder\Api\TemplateRepositoryInterface;
use Magezon\PageBuilder\Model\TemplateFactory;

/**
 * Save template controller.
 */
class Save extends Action
{
    const ADMIN_RESOURCE = 'Magezon_PageBuilder::template_save';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * Constructor.
     *
     * @param Action\Context $context
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param TemplateFactory $templateFactory
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(
        Action\Context $context,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        TemplateFactory $templateFactory,
        TemplateRepositoryInterface $templateRepository
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
        $this->templateFactory = $templateFactory;
        $this->templateRepository = $templateRepository;
    }

    /**
     * Save action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->request->getPostValue();
        $redirectBack = $this->request->getParam('back', false);
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $templateId = (string) $this->request->getParam('template_id');
            $model = $this->templateFactory->create();

            try {
                if ($templateId) {
                    $model = $this->templateRepository->getById($templateId);
                }
                $model->setData($data);
                $this->templateRepository->save($model);

                $this->messageManager->addSuccessMessage(__('You saved the template.'));
                $this->dataPersistor->clear('current_template');

                if ($redirectBack === 'save_and_new') {
                    return $resultRedirect->setPath('*/*/new');
                }

                if ($redirectBack === 'save_and_duplicate') {
                    $duplicate = $this->templateFactory->create();
                    $duplicate->setData($model->getData());
                    $duplicate->setId(null);
                    $this->templateRepository->save($duplicate);

                    $this->messageManager->addSuccessMessage(__('You duplicated the template.'));
                    return $resultRedirect->setPath('*/*/edit', [
                        'template_id' => $duplicate->getId(),
                        '_current' => true
                    ]);
                }

                if ($redirectBack === 'save_and_close') {
                    return $resultRedirect->setPath('*/*/');
                }

                return $resultRedirect->setPath('*/*/edit', [
                    'template_id' => $model->getId(),
                    '_current' => true
                ]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the template.'));
            }

            $this->dataPersistor->set('current_template', $data);
            return $resultRedirect->setPath('*/*/edit', [
                'template_id' => $this->request->getParam('template_id'),
                '_current' => true
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
