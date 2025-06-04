# Refactor categories.phtml

Template `view/frontend/templates/element/categories.phtml` uses helpers and `$this` which are discouraged.

- Convert helper usage to a ViewModel and inject dependencies via the layout.
- Replace `$this` with `$block` variables in the template.
