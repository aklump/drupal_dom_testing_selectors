<?php

namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use Drupal\Core\Template\Attribute;

/**
 * @code
 * $link_element = [
 *   '#type' => 'link',
 *   '#options' => [
 *     'attributes' => [],
 *   ],
 * ];
 * @endcode
 */
class DrupalLinkHandler implements HandlerInterface {

  public function canHandle($element): bool {
    return is_array($element) && isset($element['#type']) && $element['#type'] === 'link';
  }

  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    $name = $selector->getAttributeName();
    $element['#options']['attributes'][$name] = $selector->getAttributeValue();
  }
}
