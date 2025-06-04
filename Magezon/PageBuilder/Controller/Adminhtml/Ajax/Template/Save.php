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

namespace Magezon\PageBuilder\Controller\Adminhtml\Ajax\Template;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magezon\PageBuilder\Api\TemplateRepositoryInterface;
use Magezon\PageBuilder\Model\TemplateFactory;

/**
 * Save template action for Page Builder.
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session.
     */
    const ADMIN_RESOURCE = 'Magezon_PageBuilder::template_save';

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param TemplateFactory $templateFactory
     * @param TemplateRepositoryInterface $templateRepository
     * @param HttpRequest $request
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        TemplateFactory $templateFactory,
        TemplateRepositoryInterface $templateRepository,
        HttpRequest $request
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->templateFactory = $templateFactory;
        $this->templateRepository = $templateRepository;
        $this->request = $request;
    }

    /**
     * Save action.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = ['status' => false];

        $data = $this->request->getPostValue();

        if ($data) {
            try {
                $template = $this->templateFactory->create();
                $template->setData($data);

                $this->templateRepository->save($template);

                $this->messageManager->addSuccessMessage(__('You saved the template.'));
                $result['status'] = true;
            } catch (LocalizedException $e) {
                $result['message'] = $e->getMessage();
            } catch (\Exception $e) {
                $result['message'] = __('Something went wrong while saving the template.');
            }
        }

        return $resultJson->setData($result);
    }
}
