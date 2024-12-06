<?php

namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use Drupal\Core\Template\Attribute;

class DrupalArrayWithOneAttributeHandler implements HandlerInterface {

  public function canHandle($element): bool {
    return count($this->getAttributeKeys($element)) === 1;
  }

  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    $key = $this->getAttributeKeys($element)[0];
    $element[$key]->offsetSet($selector->getAttributeName(), $selector->getAttributeValue());
  }

  private function getAttributeKeys(array $array) {
    return array_keys(array_filter($array, function ($value) {
      return $value instanceof Attribute;
    }));
  }
}
