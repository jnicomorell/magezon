
# Compatibility with Magento 2.4.7 – Unicomer_FiscalCredit - 15/05/2025

## Context

This module was updated as part of the migration process to Magento 2.4.7.
The Upgrade Compatibility Tool was used to detect incompatible or deprecated classes, methods, and patterns.
Below are the warnings considered false positives and the actual modifications made to ensure compatibility.

## ✅ Changes made

### 1. Error `app\code\Magezon\Builder\view\frontend\web\js\magezon-builder.js`
## Error: 
Instead of .click(fn) use .on("click", fn). Instead of .click() use .trigger("click")
Instead of .click(fn) use .on("click", fn). Instead of .click() use .trigger("click")
Instead of .click(fn) use .on("click", fn). Instead of .click() use .trigger("click")

## Correction:
- Use .on('click', fn). 
- Replace $(this).click(function(e) -> $(this).on('click', function(e).

### 2. Error `app\code\Magezon\Builder\view\frontend\templates\head.phtml`
## Error: 
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Replace $this -> $block

### 3. Error `app\code\Magezon\Builder\view\frontend\templates\head.phtml`
## Error: 
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Replace $this -> $block
- Add ViewModel -> ViewModel/Config.php
- Register -> layout/default.xml

### 4. Error `app\code\Magezon\Builder\view\frontend\templates\element\text.phtml`
## Error: 
The use of helpers in templates is discouraged. Use ViewModel instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Replace $this -> $block
- Add ViewModel -> ViewModel/TextContent.php
- Register -> layout/magezon_builder_text.xml

### 5. Error `app\code\Magezon\Builder\view\frontend\templates\element\tabs.phtml`
## Error: 
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Replace $this -> $block

### 6. Error `app\code\Magezon\Builder\view\frontend\templates\element\social_icons.phtml`
## Error: 
The use of helpers in templates is discouraged. Use ViewModel instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Add ViewModel -> ViewModel/SocialIcons.php
- Replace $this -> $block
- Register -> layout/magezon_builder_socialicons.xml

### 7. Error `app\code\Magezon\Builder\view\frontend\templates\element\single_image.phtml`
## Error: 
The use of helpers in templates is discouraged. Use ViewModel instead.
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Add ViewModel -> ViewModel/SingleImage.php
- Replace $this -> $block
- Register -> layout/magezon_builder_singleimage.xml

### 8. Error `app\code\Magezon\Builder\view\frontend\templates\element\magento_widgget.phtml`
## Error: 
The use of helpers in templates is discouraged. Use ViewModel instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Add ViewModel -> ViewModel/MagentoWidgetViewModel.php
- Replace $this -> $block
- Register -> layout/magezon_builder_magento_widget.xml

### 9. Error `app\code\Magezon\Builder\view\frontend\templates\element\heading.phtml`
## Error: 
The use of helpers in templates is discouraged. Use ViewModel instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Add ViewModel -> ViewModel/HeadingViewModel.php
- Replace $this -> $block
- Register -> layout/magezon_builder_heading.xml

### 10. Error `app\code\Magezon\Builder\view\frontend\templates\element\gmaps.phtml`
## Error: 
The use of helpers in templates is discouraged. Use ViewModel instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Add ViewModel -> ViewModel/GmapsViewModel.php
- Replace $this -> $block
- Register -> layout/magezon_builder_gmaps.xml

### 11. Error `app\code\Magezon\Builder\view\base\web\vendor\spectrum\tinycolor.js`
## Error: 
Avoid using self-closing tag with non-void html element - "<http://www.w3.org/TR/css3-color/>\n

## Correction:
- Replace <http://www.w3.org/TR/css3-color/> -> [http://www.w3.org/TR/css3-color/]

### 12. Error `app\code\Magezon\Builder\view\base\web\vendor\spectrum\spectrum.js`
## Error: 
Avoid using self-closing tag with non-void html element - "<span class="sp-thumb-inner" style="' + swatchStyle + ';" />\n  
Avoid using self-closing tag with non-void html element - "<div />\n
Avoid using self-closing tag with non-void html element - "<http://www.w3.org/TR/css3-color/>\n 

## Correction:
- Replace close tags sintaxis HTML

### 13. Error `app\code\Magezon\Builder\view\base\web\vendor\angular-ui-select\dist\select.js`
## Error: 
Avoid using self-closing tag with non-void html element - "<ui-select-multiple/>\n
Avoid using self-closing tag with non-void html element - "<ui-select-single/>\n

## Correction:
- Replace close tags sintaxis HTML

### 14. Error `app\code\Magezon\Builder\view\base\web\js\waypoints\shortcuts\sticky.js`
## Error: 
Avoid using self-closing tag with non-void html element - "<div class="sticky-wrapper" />\n

## Correction:
- Replace close tags sintaxis HTML

### 15. Error `app\code\Magezon\Builder\view\base\web\js\services\editor.js`
## Error: 
'modern' theme is removed. Update code to be compatible with tinymce5
file_browser_callback is removed. Update code to be compatible with tinymce5
file_browser_callback is removed. Update code to be compatible with tinymce5

## Correction:
- Replace 'theme: "silver"' -> 'theme: "silver",'
- Replace file_browser_callback -> file_picker_callback

### 16. Error `app\code\Magezon\Builder\view\base\web\js\form\element\dynamic-rows\radio.js`
## Error: 
Instead of .click(fn) use .on("click", fn). Instead of .click() use .trigger("click")

## Correction:
- Use .on('click', fn). 
- Replace $(this).click(function(e) -> $(this).on('click', function(e).

### 17. Error `app\code\Magezon\Builder\view\base\web\js\controllers\list.js`
## Error: 
jQuery.isArray() is deprecated. Use the native Array.isArray method instead

## Correction:
- Replace $.isArray() -> Array.isArray()

### 18. Error `app\code\Magezon\Builder\view\base\web\js\controllers\list.js`
## Error: 
Please do not use "text/javascript" type attribute.

## Correction:
- Delete type="text/javascript"

### 19. Error `app\code\Magezon\Builder\view\base\web\js\controllers\list.js`
## Error: 
Please do not use "text/javascript" type attribute.

## Correction:
- Delete type="text/javascript"

### 20. Error `app\code\Magezon\Builder\Ui\Component\Form\Element\Builder.php`
## Error: 
Using class 'Magento\Framework\Data\Form\Element\Editor' that is non API on version '2.4.7'
Call method 'Magento\Framework\Data\Form\Element\Editor::getElementHtml' that is non API on version '2.4.7'

## Correction:
- registerReplacement to get the valid page layouts

### 21. Error `app\code\Magezon\Builder\Helper\Data.php`
## Error: 
The use of function stripslashes() is discouraged
The use of function stripslashes() is discouraged

## Correction:
- Replace  stripslashes($match[2]) -> $match[2]

### 22. Error `app\code\Magezon\Builder\Helper\Data.php`
## Error: 
The use of function stripslashes() is discouraged
The use of function stripslashes() is discouraged
Instantiating class/interface 'Magento\Cms\Model\ResourceModel\Page\Collection' that is non API on version '2.4.7
Instantiating class/interface 'Magento\Framework\Filter\Template\Tokenizer\Parameter' that is non API on version '2.4.7'
Using class 'Magento\Cms\Model\ResourceModel\Page\Collection' that is non API on version '2.4.7'

## Correction:
- Replace  stripslashes($match[2]) -> $match[2]
- Delete Magento\Cms\Model\ResourceModel\Page\Collection
- Use PageRepositoryInterface with SearchCriteriaBuilder

### 23. Error `app\code\Magezon\Builder\Controller\Adminhtml\Widget\LoadOptions.php`
## Error: 
Extending from class 'Magento\Widget\Controller\Adminhtml\Widget\LoadOptions' that is non API on version '2.4.7'
Extending from class 'Magento\Widget\Controller\Adminhtml\Widget\LoadOptions' that is non API on version '2.4.7'

## Correction:
- Delete Magento\Widget\Controller\Adminhtml\Widget\LoadOptions
- Add layout/mgz_widget_loadoptions.xml

### 24. Error `app\code\Magezon\Builder\Controller\Adminhtml\Widget\Index.php`
## Error: 
Extending from class 'Magento\Widget\Controller\Adminhtml\Widget\Index' that is non API on version '2.4.7'

## Correction:
- Add layout/mgz_widget_index.xml

### 25. Error `app\code\Magezon\Builder\Controller\Adminhtml\Widget\BuildWidget.php`
## Error: 
Extending from class 'Magento\Widget\Controller\Adminhtml\Widget\BuildWidget' that is non API on version '2.4.7'

## Correction:
- Rewrite Controller

### 26. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\Template.php`
## Error: 
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getResponse' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7'

## Correction:
- Inject RequestInterface
- Replace $this->getResponse()->setBody(...) -> Result\Json

### 27. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\LoadStyles.php`
## Error: 
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getResponse' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7'

## Correction:
- Inject RequestInterface and Result\JsonFactory, return JSON.
- Replace $this->getRequest() -> $this->request
- Delete $this->getResponse()->setBody() and
 Json\Helper\Data

### 28. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\LoadElement.php`
## Error: 
Call method 'Magento\Backend\App\Action\Context::create' that does not exist on version '2.4.7'

## Correction:
- Property $resultRawFactory is type RawFactory, Use Magento\Framework\Controller\Result\RawFactory.

### 29. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\LoadConfig.php`
## Error: 
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7' 
 

## Correction:
- Use Magento\Framework\App\RequestInterface $request
- Use Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
- Replace $this->request -> $this->getRequest()
- Replace $this->resultJsonFactory->create()->setData($result) -> getResponse() and Json\Helper\Data

### 30. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\LibraryTemplate.php`
## Error: 
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7' 
 

## Correction:
- Use Magento\Framework\App\RequestInterface $request
- Use Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
- Replace $this->request -> $this->getRequest()
- Replace $this->resultJsonFactory->create()->setData($result) -> getResponse() and Json\Helper\Data

### 31. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\ItemList.php`
## Error: 
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7' 

## Correction:
- Use Magento\Framework\App\RequestInterface $request
- Use Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
- Replace $this->request -> $this->getRequest()
- Replace $this->resultJsonFactory->create()->setData($result) -> getResponse() and Json\Helper\Data

### 32. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\ItemInfo.php`
## Error: 
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getResponse' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7' 

## Correction:
- Use Magento\Framework\App\RequestInterface $request
- Use Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
- Replace $this->request -> $this->getRequest()
- Replace $this->resultJsonFactory->create()->setData($result) -> getResponse() and Json\Helper\Data

### 33. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\ConditionsValue.php`
## Error: 
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
The use of function parse_str() is discouraged
Call method 'Magento\Framework\App\Action\AbstractAction::getResponse' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7' 

## Correction:
- Use Magento\Framework\App\RequestInterface $request
- Replace parse_str() for manual.
- Use Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
- Replace $this->request -> $this->getRequest()
- Replace $this->resultJsonFactory->create()->setData($result) -> getResponse() and Json\Helper\Data

### 34. Error `app\code\Magezon\Builder\Controller\Adminhtml\Ajax\Conditions.php`
## Error: 
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getResponse' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7'

## Correction:
- Use Magento\Framework\App\RequestInterface $request
- Use Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
- Replace $this->request -> $this->getRequest()
- Replace $this->resultJsonFactory->create()->setData($result) -> getResponse() and Json\Helper\Data
