# Fix magic method usage in Ui Component Builder

Line 127 of `app/code/Magezon/PageBuilder/Ui/Component/Form/Element/Builder.php` uses `$context->getRegistry()` which is not part of the interface.

- Determine if `ContextInterface` exposes a different method or if a dependency on `Registry` should be injected separately.
