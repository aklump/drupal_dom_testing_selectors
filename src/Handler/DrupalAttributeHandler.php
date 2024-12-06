<?php

namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use Drupal\Core\Template\Attribute;

class DrupalAttributeHandler implements HandlerInterface {

  public function canHandle($element): bool {
    return $element instanceof Attribute;
  }

  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    $element->offsetSet($selector->getAttributeName(), $selector->getAttributeValue());
  }

}
