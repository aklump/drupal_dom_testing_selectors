<?php

namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use Drupal\Core\Template\Attribute;

class DrupalArrayWithOneAttributeHandler implements HandlerInterface {

  use HandlerTrait;

  public function canHandle($element): bool {
    return is_array($element) && count($this->getAttributeKeys($element)) === 1;
  }

  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    $key = $this->getAttributeKeys($element)[0];
    $attribute_name = $selector->getAttributeName();
    $current_value = $element[$key]->offsetGet($attribute_name);
    $current_value = $current_value ? $current_value->getValue() : '';
    $attribute_value = $this->getAttributeValueBySelector($selector, $current_value);
    $element[$key]->offsetSet($attribute_name, $attribute_value);
  }

  private function getAttributeKeys(array $array) {
    return array_keys(array_filter($array, function ($value) {
      return $value instanceof Attribute;
    }));
  }
}
