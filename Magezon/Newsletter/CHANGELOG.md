# Compatibility with Magento 2.4.7 – Unicomer_FiscalCredit - 15/05/2025

## Context

This module was updated as part of the migration process to Magento 2.4.7.
The Upgrade Compatibility Tool was used to detect incompatible or deprecated classes, methods, and patterns.
Below are the warnings considered false positives and the actual modifications made to ensure compatibility.

## ✅ Changes made

### 1. Error `app\code\Magezon\Newsletter\view\frontend\templates\subscriber.phtml`
## Error: 
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Replace $this-> -> $block->

### 2. Error `app\code\Magezon\Newsletter\view\frontend\templates\element\newsletter_form.phtml`
## Error:
The use of $this in templates is deprecated. Use $block instead.

## Correction:
- Replace $this-> -> $block->

### 3. Error `app\code\Magezon\Newsletter\Controller\Subscriber\NewAction.php`
## Error:
Extending from class 'Magento\Newsletter\Controller\Subscriber\NewAction' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7'
Extending from class 'Magento\Framework\App\Action\Action' that is @deprecated on version '2.4.7' - See \Magento\Framework\App\ActionInterface
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'

## Correction:
- Replace extend from Magento\Framework\App\Action\Action
- Replace Json\Helper\Data (no API) -> JsonFactory
- Delete ObjectManager
- Use ActionInterface
- Replace getRequest() -> $request
- Replace getPost() -> getParam()

### 4. Error `app\code\Magezon\Newsletter\Controller\Subscriber\Email.php`
## Error:
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getRequest' that is non API on version '2.4.7'
Call method 'Magento\Framework\App\Action\AbstractAction::getResponse' that is non API on version '2.4.7'
Using class 'Magento\Framework\Json\Helper\Data' that is non API on version '2.4.7' 

## Warning:
Extending from class 'Magento\Framework\App\Action\Action' that is @deprecated on version '2.4.7' - See \Magento\Framework\App\ActionInterface
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'
Non declared method usage. Possibly DataObject magic method call: 'Magento\Framework\App\RequestInterface::getPost'

## Correction:
- Replace extends Action -> implements ActionInterface
- getRequest() (no API) -> inject RequestInterface
- Delete Json\Helper\Data (no API), Use JsonFactory
- Replace getPost() -> getParam()
- Replace getResponse()->representJson() (no API) -> JsonFactory->setData()

### 5. Error `pp\code\Magezon\Newsletter\Setup\InstallSchema.php`
## Error:
InstallSchema scripts are obsolete. Please use declarative schema approach in module's etc/db_schema.xml file

## Correction:
- Delete Newsletter\Setup\InstallSchema.php
- Add etc/db_schema.xml

## Final status

✔ No critical errors.
✔ 0 errors.
✔ 0 warnings.
