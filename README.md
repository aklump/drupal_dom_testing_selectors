# DOM Testing Selectors Drupal Module

![Hero](images/drupal_dom.jpg)

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

## Install with Composer

Because this is an unpublished, custom Drupal extension, the way you install and depend on it is a little different than published, contributed extensions.

* Add the following to the **root-level** _composer.json_ in the `repositories` array:
    ```json
    {
     "type": "github",
     "url": "https://github.com/aklump/drupal_dom_testing_selectors"
    }
    ```
* Add the installed directory to **root-level** _.gitignore_
  
   ```php
   /web/modules/custom/dom_testing_selectors/
   ```
* Proceed to either A or B, but not both.
---
### A. Install Standalone
* Require _dom_testing_selectors_ at the **root-level**.
    ```
    composer require aklump_drupal/dom_testing_selectors:^0.0
    ```
---
### B. Depend on This Module
(_Replace `my_module` with your module's real name._)

* Add the following to _my_module/composer.json_ in the `repositories` array. (_Yes, this is done both here and at the root-level._)
    ```json
    {
     "type": "github",
     "url": "https://github.com/aklump/drupal_dom_testing_selectors"
    }
    ```
* From the depending module directory run:
    ```
    composer require aklump_drupal/dom_testing_selectors:^0.0 --no-update
    ```

* Add the following to _my_module.info.yml_ in the `dependencies` array:
    ```yaml
    aklump_drupal:dom_testing_selectors
    ```
* Back at the **root-level** run `composer update my_module`


---
### Enable This Module

* Re-build Drupal caches, if necessary.
* Enable this module, e.g.,
  ```shell
  drush pm-install dom_testing_selectors
  ```

## Usage

You will most often use this with render arrays as shown here.

```php
$render_element = [
  '#type' => 'tag',
  '#tag' => 'h1',
];

\Drupal\dom_testing_selectors\TestingSelectors::apply($render_element, 'page_title');
```

```text
Array
(
    [#type] => tag
    [#tag] => h1
    [#attributes] => Array
        (
            [data-test] => page_title
        )

)

```

### Grouping Testing Selectors

```php
$render_elements = [];

/** @var string $group The test group for the markers. */
$group = 'typeface';

$render_element = [
  '#type' => 'tag',
  '#tag' => 'h1',
];
TestingSelectors::apply($render_element, 'page_title', $group);
$render_elements[] = $render_element;

$render_element = [
  '#type' => 'tag',
  '#tag' => 'h2',
];
TestingSelectors::apply($render_element, 'page_subtitle', $group);
$render_elements[] = $render_element;
```

```text
Array
(
    [0] => Array
        (
            [#type] => tag
            [#tag] => h1
            [#attributes] => Array
                (
                    [data-test] => typeface__page_title
                )

        )

    [1] => Array
        (
            [#type] => tag
            [#tag] => h2
            [#attributes] => Array
                (
                    [data-test] => typeface__page_subtitle
                )

        )

)

```

So far the examples have shown render arrays being used. This also works with other types of content.

### String Content

```php
$html = '<h1>Lorem ipsum</h1>';
TestingSelectors::apply($html, 'page_title');
```

```text
<h1 data-test="page_title">Lorem ipsum</h1>
```

### Tables

The simplest way of marking up a table is to pass the table render array as shown. It's also possible to pass single rows, and even single cells, if you want full control of the testing selectors. See more examples below.

Unlike passing a row or a cell to `::setTestingSelector`, when you pass the entire table, the use of the `data` key is not required.

```php
$table = [
  '#theme' => 'table',
  '#header' => ['Name', 'Age'],
  '#rows' => [
    ['Andrew', 36],
    ['Laurie', 34],
  ],
];
TestingSelectors::apply($table, 'members_list');
```

```text
Array
(
    [#theme] => table
    [#header] => Array
        (
            [0] => Name
            [1] => Age
        )

    [#rows] => Array
        (
            [0] => Array
                (
                    [data] => Array
                        (
                            [0] => Array
                                (
                                    [data] => Andrew
                                    [data-test] => members_list_r1_c1
                                )

                            [1] => Array
                                (
                                    [data] => 36
                                    [data-test] => members_list_r1_c2
                                )

                        )

                    [data-test] => members_list_r1
                )

            [1] => Array
                (
                    [data] => Array
                        (
                            [0] => Array
                                (
                                    [data] => Laurie
                                    [data-test] => members_list_r2_c1
                                )

                            [1] => Array
                                (
                                    [data] => 34
                                    [data-test] => members_list_r2_c2
                                )

                        )

                    [data-test] => members_list_r2
                )

        )

    [#attributes] => Array
        (
            [data-test] => members_list
        )

)

```

Notice what happens to the column testing selectors if the column data is not numerically indexed, e.g., `first_name` and `current_age`. The `#headers` array does not determine the testing selector names, but rather, the keys.

```php
$table = [
  '#theme' => 'table',
  '#header' => ['Name', 'Age'],
  '#rows' => [
    ['first_name' => 'Andrew', 'current_age' => 36],
  ],
];
TestingSelectors::apply($table, 'members_list');
```

```text
Array
(
    [#theme] => table
    [#header] => Array
        (
            [0] => Name
            [1] => Age
        )

    [#rows] => Array
        (
            [0] => Array
                (
                    [data] => Array
                        (
                            [first_name] => Array
                                (
                                    [data] => Andrew
                                    [data-test] => members_list_r1_first_name
                                )

                            [current_age] => Array
                                (
                                    [data] => 36
                                    [data-test] => members_list_r1_current_age
                                )

                        )

                    [data-test] => members_list_r1
                )

        )

    [#attributes] => Array
        (
            [data-test] => members_list
        )

)

```

### Table Rows

The Drupal table API does not require that a table row use the `data` key, however when passing a table row to `::setTestingSelector` **you must use the `data` key.**

```php
$table = [
  '#theme' => 'table',
  '#rows' => [
    ['data' => ['name' => 'Andrew', 'age' => 36]],
  ],
];
TestingSelectors::apply($table['#rows'][0], 'andrew_data', 'members_table');
```

```text
Array
(
    [#theme] => table
    [#rows] => Array
        (
            [0] => Array
                (
                    [data] => Array
                        (
                            [name] => Andrew
                            [age] => 36
                        )

                    [data-test] => members_table__andrew_data
                )

        )

)

```

### Table Cells

The Drupal table API does not require that a table cell use the `data` key, however when passing a table cell to `::setTestingSelector` **you must use the `data` key.**

```php
$table = [
  '#theme' => 'table',
  '#rows' => [
    [['data' => 'Andrew'], ['data' => 36]]
  ],
];
TestingSelectors::apply($table['#rows'][0][0], 'first_name', 'members_table');
```

```text
Array
(
    [#theme] => table
    [#rows] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [data] => Andrew
                            [data-test] => members_table__first_name
                        )

                    [1] => Array
                        (
                            [data] => 36
                        )

                )

        )

)

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
