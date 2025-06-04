# Changelog

## [Unreleased]

### Changed
- Refactorizado `view/frontend/templates/element/iconbox.phtml` para cumplir con los estándares de Magento 2.4.7.

### Detalles del cambio

**Problemas solucionados:**
- 🚫 Uso de `$this` en plantilla `.phtml` — reemplazado por `$block`.
- 🚫 Uso directo de helper en plantilla (`$this->helper(...)`) — reemplazado por `ViewModel`.
- 🔐 Falta de escapado de variables — solucionado usando `escapeHtml`, `escapeHtmlAttr`, y `escapeUrl`.
- 📦 Lógica de negocio mezclada con presentación — separada mediante un `ViewModel`.

**Implementaciones:**
- Se creó `Magezon\PageBuilderIconBox\ViewModel\IconBoxData` como ViewModel para manejar lógica de negocio.
- Se agregó definición del ViewModel en el layout XML correspondiente.
- Se actualizó la plantilla `iconbox.phtml` utilizando únicamente `$block` y `$viewModel`.
- Todas las salidas dinámicas están ahora correctamente escapadas.

### Archivos afectados:
- `view/frontend/templates/element/iconbox.phtml`
- `view/frontend/layout/magezon_pagebuilder_iconbox.xml` (nuevo o modificado)
- `ViewModel/IconBoxData.php` (nuevo)

---
