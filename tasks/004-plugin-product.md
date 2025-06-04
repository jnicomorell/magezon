# Resolve DataObject magic call in Plugin/Model/Product.php

UCT warns that line 106 of `app/code/Magezon/PageBuilder/Plugin/Model/Product.php` uses `$request->getFullActionName()` which may rely on magic methods.

- Inject the correct request object implementing this method or adjust the code to use supported APIs.
