<?php

namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use Drupal\Core\Template\Attribute;

class DrupalAttributeHandler implements HandlerInterface {

  use HandlerTrait;

  public function canHandle($element): bool {
    return $element instanceof Attribute;
  }

  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    $attribute_name = $selector->getAttributeName();
    $current_value = $element->offsetGet($attribute_name);
    $current_value = $current_value ? $current_value->value() : '';
    $attribute_value = $this->getAttributeValueBySelector($selector, $current_value);
    $element->offsetSet($attribute_name, $attribute_value);

  }

}
