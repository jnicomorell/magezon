# Update RawHtmlData ViewModel

`app/code/Magezon/PageBuilder/ViewModel/RawHtmlData.php` uses `$context->getView()` on line 20 which is not declared on the interface.

- Inject the necessary dependency that provides the view or refactor the code to avoid using this method.
