<?php
// SPDX-License-Identifier: GPL-2.0-or-later
namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use InvalidArgumentException;

/**
 * Handlers Drupal render arrays, which are indicated by having at least on key
 * that begins with '#'.  The selector is added to the top level '#attributes'.
 */
class DrupalRenderArrayHandler implements HandlerInterface {

  /**
   * {@inheritdoc}
   */
  public function canHandle($element): bool {
    if (!is_array($element)) {
      return FALSE;
    }
    $keys = array_filter(array_keys($element), function ($key) {
      return substr($key, 0, 1) === '#';
    });

    return !empty($keys);
  }

  /**
   * {@inheritdoc}
   */
  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    if (!$this->canHandle($element)) {
      throw new InvalidArgumentException();
    }

    $current_value = $this->getCurrentValue($element, $selector);
    if (!is_array($current_value)) {
      $attribute_value = $selector->getAttributeValue($current_value);
    }
    else {
      $current_value = implode(' ', $current_value);
      $attribute_value = $selector->getAttributeValue($current_value);
      $attribute_value = explode(' ', $attribute_value);
    }

    $element['#attributes'][$selector->getAttributeName()] = $attribute_value;
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

    return $element['#attributes'][$selector->getAttributeName()] ?? $fallback_value;
  }
}
