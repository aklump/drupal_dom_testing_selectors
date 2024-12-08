<?php
// SPDX-License-Identifier: GPL-2.0-or-later
namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use Drupal\Core\Render\Element;
use InvalidArgumentException;

class DrupalTableHandler implements HandlerInterface {

  use HandlerTrait;

  /**
   * {@inheritdoc}
   */
  public function canHandle($element): bool {
    return is_array($element) && isset($element['#rows']) && isset($element['#theme']) && $element['#theme'] === 'table';
  }

  /**
   * {@inheritdoc}
   */
  public function setTestingSelectorOnElement(&$table_element, ElementSelectorInterface $selector): void {
    $selector_attribute_name = $selector->getAttributeName();
    $current_value = $table_element['#attributes'][$selector_attribute_name] ?? '';
    $base__testing_selector = $this->getAttributeValueBySelector($selector, $current_value);
    $table_element['#attributes'][$selector_attribute_name] = $base__testing_selector;

    $row_index = 1;
    foreach ($table_element['#rows'] as &$row) {
      $this->placeInDataKeyIfMissing($row);

      $row__current_value = $row[$selector_attribute_name] ?? '';
      $row_selector = clone $selector;
      $row_selector->setName($base__testing_selector . '_r' . $row_index);

      $row__testing_selector = $this->getAttributeValueBySelector($row_selector, $row__current_value);
      $row[$selector_attribute_name] = $row__testing_selector;
      $cell_index = 1;
      foreach ($row['data'] as $cell_key => &$cell) {
        $row_selector->setName($row__testing_selector . $this->getCellTestingSelector($cell_key, $cell_index));
        $this->placeInDataKeyIfMissing($cell);
        $cell__current_value = $cell[$selector_attribute_name] ?? '';
        $cell[$selector_attribute_name] = $this->getAttributeValueBySelector($row_selector, $cell__current_value);
        ++$cell_index;
      }
      unset($cell);
      ++$row_index;
    }
    unset($row);
  }

  private function placeInDataKeyIfMissing(&$array) {
    if (is_array($array) && array_key_exists('data', $array)) {
      return;
    }

    $array = ['data' => $array];
  }

  private function getCellTestingSelector($cell_key, int $cell_index): string {
    if (!is_numeric($cell_key)) {
      return '_' . $cell_key;
    }

    return '_c' . $cell_index;
  }
}
