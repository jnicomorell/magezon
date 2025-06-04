# Fix non-API method call in Builder/Load.php

The Upgrade Compatibility Tool reports a non-API call at line 69 of `app/code/Magezon/PageBuilder/Controller/Adminhtml/Builder/Load.php`.

- Replace usage of `\Magento\Framework\App\Action\AbstractAction::getRequest()` with dependency injection of `RequestInterface` in the constructor.
- Ensure the request parameters are retrieved using declared API methods, e.g. `$this->request->getParam()`.
