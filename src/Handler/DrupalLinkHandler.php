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

  use HandlerTrait;

  public function canHandle($element): bool {
    return is_array($element) && isset($element['#type']) && $element['#type'] === 'link';
  }

  public function setTestingSelectorOnElement(&$element, ElementSelectorInterface $selector): void {
    $attribute_name = $selector->getAttributeName();
    $current_value = $element['#options']['attributes'][$attribute_name] ?? '';
    $element['#options']['attributes'][$attribute_name] = $this->getAttributeValueBySelector($selector, $current_value);
  }

}
