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

  /**
   * {@inheritdoc}
   */
  public function canHandle($element): bool {
//    if ($this->isSimpleRow($element)) {
//      return TRUE;
//    }

    return is_array($element) && array_key_exists('data', $element) && !Element::properties($element);
//    return is_array($element) && array_key_exists('data', $element) && is_array($element['data']);
  }

  private function isSimpleRow($element): bool {
    return is_array($element) && count($element) === 1 && isset($element[0]) && is_scalar($element[0]);
  }

  /**
   * {@inheritdoc}
   */
  public function setTestingSelectorOnElement(&$table_row, ElementSelectorInterface $selector): void {
    if (!$this->canHandle($table_row)) {
      throw new InvalidArgumentException();
    }

    if ($this->isSimpleRow($table_row)) {
      $table_row = ['data' => $table_row];
    }


    // TODO If class can be a string then we need to handle that scenario!
    $current_value = $this->getCurrentValue($table_row, $selector);
    if (!is_array($current_value)) {
      $attribute_value = $selector->getAttributeValue($current_value);
    }
    else {
      $current_value = implode(' ', $current_value);
      $attribute_value = $selector->getAttributeValue($current_value);
      $attribute_value = explode(' ', $attribute_value);
    }

    $table_row[$selector->getAttributeName()] = $attribute_value;
  }

  /**
   * Get the current value of an element's attribute
   *
   * @param mixed $element The element from which to retrieve the attribute value.
   * @param ElementSelectorInterface $selector The selector used to determine the attribute name.
   *
   * @return mixed The current value of the attribute.
   */
  private function getCurrentValue($element, ElementSelectorInterface $selector) {
    // TODO Are there other Drupal attributes that might be stored as arrays?
    $fallback_value = $selector->getAttributeName() === 'class' ? [] : '';

    return $element[$selector->getAttributeName()] ?? $fallback_value;
  }
}
