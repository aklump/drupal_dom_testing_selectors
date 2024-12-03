<?php
// SPDX-License-Identifier: GPL-2.0-or-later
namespace Drupal\dom_testing_selectors\Handler;

use AKlump\DomTestingSelectors\Handler\HandlerInterface;
use AKlump\DomTestingSelectors\Selector\ElementSelectorInterface;
use Drupal\Core\Render\Element;
use InvalidArgumentException;

class DrupalTableHandler implements HandlerInterface {

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
    if (!$this->canHandle($table_element)) {
      throw new InvalidArgumentException();
    }

    $table_element['#attributes'][$selector->getAttributeName()] = $selector->getAttributeValue();

    $row_index = 1;
    foreach ($table_element['#rows'] as &$row) {
      $row_selector = clone $selector;
      $basename = $row_selector->getAttributeValue();
      $row_selector->setName($basename . '_r' . $row_index);

      $this->placeInDataKeyIfMissing($row);

      $row_testing_marker = $row[$row_selector->getAttributeName()] ?? $row_selector->getAttributeValue();
      $row[$row_selector->getAttributeName()] = $row_testing_marker;
      $cell_index = 1;
      foreach ($row['data'] as $cell_key => &$cell) {
        $row_selector->setName($row_testing_marker . $this->getCellName($cell_key, $cell_index));
        $this->placeInDataKeyIfMissing($cell);
        $cell[$row_selector->getAttributeName()] = $row_selector->getAttributeValue();
        ++$cell_index;
      }
      unset($cell);
      ++$row_index;
    }
    unset($row);
  }

  private function placeInDataKeyIfMissing(mixed &$array) {
    if (is_array($array) && array_key_exists('data', $array)) {
      return;
    }

    $array = ['data' => $array];
  }

  private function getCellName($cell_key, int $cell_index): string {
    if (!is_numeric($cell_key)) {
      return '_' . $cell_key;
    }

    return '_c' . $cell_index;
  }
}
