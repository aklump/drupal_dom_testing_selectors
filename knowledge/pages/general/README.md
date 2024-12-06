<!--
id: readme
tags: ''
-->

# DOM Testing Selectors Drupal Module

![Hero](../../images/drupal_dom.jpg)

## Summary

Provides a method of adding specific markup to your DOM for testing purposes.

1. Given something to render, add a testing selector `foo`, possible grouped by `bar`.

    ```html
    <div data-test="bar__foo">
      Lorem ipsum dolor sit amet...
    </div>
    ```

1. Now write tests using the testing selector, rather than reusing a class or other attribute that might change over time, breaking your tests.

    ```js
    describe('Test bar__foo element', () => {
      it('should find the element with data-test attribute', () => {
        cy.visit('https://website.com');

        cy.get('[data-test="bar__foo"]')
          .should('exist')
          .should('contain.text', 'Lorem ipsum dolor sit amet');
      });
    });
    ```

{{ composer.install|raw }}

## Usage

You will most often use this with render arrays as shown here.

```php
{{ readme_set_testing_selector|raw }}
```

```text
{{ readme_testing_selector_example|raw }}
```

### Grouping Testing Selectors

```php
{{ readme_set_testing_selector_with_group|raw }}
```

```text
{{ readme_testing_selector_with_groups_example|raw }}
```

So far the examples have shown render arrays being used. This also works with other types of content.

### String Content

```php
{{ readme_set_testing_selector_with_string|raw }}
```

```text
{{ readme_testing_selector_with_string_example|raw }}
```

### Tables

The simplest way of marking up a table is to pass the table render array as shown. It's also possible to pass single rows, and even single cells, if you want full control of the testing selectors. See more examples below.

Unlike passing a row or a cell to `::setTestingSelector`, when you pass the entire table, the use of the `data` key is not required.

```php
{{ readme_set_testing_selector_with_table|raw }}
```

```text
{{ readme_table_example|raw }}
```

Notice what happens to the column testing selectors if the column data is not numerically indexed, e.g., `first_name` and `current_age`. The `#headers` array does not determine the testing selector names, but rather, the keys.

```php
{{ readme_set_testing_selector_with_table_with_keyed_columns|raw }}
```

```text
{{ readme_table_with_keyed_columns_example|raw }}
```

### Table Rows

The Drupal table API does not require that a table row use the `data` key, however when passing a table row to `::setTestingSelector` **you must use the `data` key.**

```php
{{ readme_set_testing_selector_with_rows|raw }}
```

```text
{{ readme_rows_only_example|raw }}
```

### Table Cells

The Drupal table API does not require that a table cell use the `data` key, however when passing a table cell to `::setTestingSelector` **you must use the `data` key.**

```php
{{ readme_set_testing_selector_with_cells|raw }}
```

```text
{{ readme_cells_only_example|raw }}
```

## Disabling Markup

To prevent the testing selectors from being added to your content, you should replace the `dom_testing_selectors.factory` service as shown below, [learn more](https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/altering-existing-services-providing-dynamic-services). You must rebuild cache for the change to take affect.

In this way, it's possible to add some logic that will only replace the class on the live server, thereby disabling test markup there.

```php
namespace Drupal\my_module;

class MyModuleServiceProvider implements \Drupal\Core\DependencyInjection\ServiceModifierInterface {

  public function alter(\Drupal\Core\DependencyInjection\ContainerBuilder $container): void {
    // Get an existing service and modify it.
    if ($container->hasDefinition('dom_testing_selectors.factory')) {
      $definition = $container->getDefinition('dom_testing_selectors.factory');
      $definition->setClass(\Drupal\dom_testing_selectors\Factory\NoSelectorsFactory::class);
    }
  }

}
```

## Known Plugins

* [Loft Core Cypress Selector](https://github.com/aklump/loft-core-cypress-selector)
