<?php
namespace Magezon\PageBuilder\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;

class ContactPostRedirect implements ObserverInterface
{
    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * @param RedirectInterface $redirect
     */
    public function __construct(RedirectInterface $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $controller = $observer->getEvent()->getControllerAction();
        $request = $observer->getEvent()->getRequest();
        if ($request->getParam('mgz')) {
            $controller->getResponse()->setRedirect($this->redirect->getRefererUrl());
        }
    }
}
