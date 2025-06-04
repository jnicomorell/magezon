# Resolve DataObject magic call in Plugin/Model/Page.php

Line 98 of `app/code/Magezon/PageBuilder/Plugin/Model/Page.php` calls `$request->getControllerName()` which is not declared in the interface.

- Verify the request type injected and use interface methods like `getControllerName()` from the appropriate class if available.
- If not possible, refactor the logic to avoid using the magic method.
