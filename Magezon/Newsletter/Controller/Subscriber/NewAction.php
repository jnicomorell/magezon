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
 * @package   Magezon_Newsletter
 * @copyright Copyright (C) 2020 Magezon (https://www.magezon.com)
 */

namespace Magezon\Newsletter\Controller\Subscriber;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Data\Form\FormKey\Validator;

class NewAction implements ActionInterface
{
    private JsonFactory $jsonFactory;
    private SubscriberFactory $subscriberFactory;
    private RequestInterface $request;
    private Validator $formKeyValidator;

    public function __construct(
        JsonFactory $jsonFactory,
        SubscriberFactory $subscriberFactory,
        RequestInterface $request,
        Validator $formKeyValidator
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->subscriberFactory = $subscriberFactory;
        $this->request = $request;
        $this->formKeyValidator = $formKeyValidator;
    }

    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $result = ['status' => false];

        if ($this->request->isAjax() && $this->request->getParam('email')) {
            $email = (string) $this->request->getParam('email');

            try {
                $subscriber = $this->subscriberFactory->create()->loadByEmail($email);
                if ($subscriber->getId() && (int) $subscriber->getSubscriberStatus() === \Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED) {
                    throw new LocalizedException(__('This email address is already subscribed.'));
                }

                $subscriber = $this->subscriberFactory->create();
                $subscriber->setImportMode(true);
                $status = (int) $subscriber->subscribe($email);

                // Campos personalizados
                $firstname = $this->request->getParam('firstname');
                $lastname = $this->request->getParam('lastname');
                $subscriber->setSubscriberFirstname($firstname);
                $subscriber->setSubscriberLastname($lastname);
                $subscriber->save();

                $result['message'] = $this->getSubscriberSuccessMessage($status);
                $result['status'] = true;
            } catch (LocalizedException $e) {
                $result['message'] = $e->getMessage();
            } catch (\Exception $e) {
                $result['message'] = __('Something went wrong with the subscription.');
            }
        }

        return $resultJson->setData($result);
    }

    private function getSubscriberSuccessMessage(int $status)
    {
        if ($status === \Magento\Newsletter\Model\Subscriber::STATUS_NOT_ACTIVE) {
            return __('The confirmation request has been sent.');
        }

        return __('Thank you for your subscription.');
    }
}
