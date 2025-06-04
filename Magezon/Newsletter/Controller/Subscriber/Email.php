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
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Framework\Controller\Result\Json;

class Email implements ActionInterface
{
    private RequestInterface $request;
    private JsonFactory $jsonFactory;
    private SubscriberFactory $subscriberFactory;

    public function __construct(
        RequestInterface $request,
        JsonFactory $jsonFactory,
        SubscriberFactory $subscriberFactory
    ) {
        $this->request = $request;
        $this->jsonFactory = $jsonFactory;
        $this->subscriberFactory = $subscriberFactory;
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
                if ($subscriber->getId()) {
                    $firstname = $this->request->getParam('firstname');
                    $lastname = $this->request->getParam('lastname');
                    $popupId = $this->request->getParam('popup_id');

                    $subscriber->setSubscriberFirstname($firstname);
                    $subscriber->setSubscriberLastname($lastname);
                    $subscriber->setPopupId($popupId);
                    $subscriber->save();

                    $result['status'] = true;
                }
            } catch (\Exception $e) {
            }
        }

        return $resultJson->setData($result);
    }
}
