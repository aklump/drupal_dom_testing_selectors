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

  use HandlerTrait;

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
    $attribute_name = $selector->getAttributeName();
    $current_value = $element['#attributes'][$attribute_name] ?? '';
    $attribute_value = $this->getAttributeValueBySelector($selector, $current_value);
    $element['#attributes'][$attribute_name] = $attribute_value;
  }

}
