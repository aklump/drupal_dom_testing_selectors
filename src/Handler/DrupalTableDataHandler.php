<?php
// SPDX-License-Identifier: GPL-2.0-or-later
namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use Drupal\Core\Render\Element;
use InvalidArgumentException;

/**
 * Handlers Drupal form row render arrays, which are indicated by having the key
 * 'data'.  The selector is added as a key to the array.
 *
 * @see \Drupal\Core\Render\Element\Table
 */
class DrupalTableDataHandler implements HandlerInterface {

  use HandlerTrait;

  /**
   * {@inheritdoc}
   */
  public function canHandle($element): bool {
    return is_array($element) && array_key_exists('data', $element) && !Element::properties($element);
  }

  /**
   * {@inheritdoc}
   */
  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    $attribute_name = $selector->getAttributeName();
    $current_value = $element['#attributes'][$attribute_name] ?? '';
    $attribute_value = $this->getAttributeValueBySelector($selector, $current_value);
    $element[$attribute_name] = $attribute_value;
  }

}
