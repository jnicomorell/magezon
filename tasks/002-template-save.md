# Fix non-API method call in Template/Save.php

UCT flagged line 82 of `app/code/Magezon/PageBuilder/Controller/Adminhtml/Template/Save.php`.

- Inject `RequestInterface` instead of using the `getRequest()` method from `AbstractAction`.
- Update the code to use `$this->request->getParam()` or `$this->request->getPostValue()` accordingly.
