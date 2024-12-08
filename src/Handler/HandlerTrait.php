<?php

namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;

trait HandlerTrait {

  /**
   * Retrieves the attribute value based on the provided selector.
   *
   * @param ElementSelectorInterface $selector The selector used to determine the attribute name and retrieve its value.
   * @param mixed $current_value The current value of the attribute, which may be an array for 'class' attributes.
   *
   * @return mixed The processed attribute value which may be a string or an array depending on the attribute type.
   * @throws \InvalidArgumentException If the current value is an array that cannot be converted properly.
   */
  public function getAttributeValueBySelector(ElementSelectorInterface $selector, $current_value) {
    $attribute_name = $selector->getAttributeName();

    // At this time we only know how to handle arrays that are 'class' attribute.
    if ('class' === $attribute_name && is_array($current_value)) {
      $current_value = implode(' ', $current_value);
    }
    if (is_array($current_value)) {
      throw new \InvalidArgumentException(sprintf('Cannot cast array value for %s', $attribute_name));
    }
    $attribute_value = $selector->getAttributeValue($current_value);
    if ('class' === $attribute_name) {
      $attribute_value = explode(' ', $attribute_value);
    }

    return $attribute_value;
  }
}
