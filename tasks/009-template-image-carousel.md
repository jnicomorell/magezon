# Refactor image_carousel.phtml

`view/frontend/templates/element/image_carousel.phtml` relies on helpers and `$this` throughout the file.

- Implement a ViewModel to supply needed data to the template.
- Replace all occurrences of `$this` with `$block` variables.
- Remove helper calls from the template and move logic to the ViewModel.
