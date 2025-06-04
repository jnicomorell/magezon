# Replace non-API class references in di.xml

`app/code/Magezon/PageBuilder/etc/di.xml` references non-API classes `Magento\Catalog\Model\Indexer\Category\Flat\Action\Full` and `Rows` at lines 657 and 660.

- Replace these with supported APIs or provide alternative implementation compatible with Magento 2.4.7.
