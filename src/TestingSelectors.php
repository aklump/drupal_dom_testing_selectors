<?php
// SPDX-License-Identifier: GPL-2.0-or-later
namespace Drupal\dom_testing_selectors;

use Drupal;

final class TestingSelectors {

  public static function apply(&$element, string $selector_name, string $selector_group = ''): void {
    $container = Drupal::getContainer();
    $selector = $container->get('dom_testing_selectors.selector');
    $selector = $selector->setName($selector_name)->setGroup($selector_group);
    $container
      ->get('dom_testing_selectors.factory')
      ->getHandler($element)
      ->setTestingSelectorOnElement($element, $selector);
  }

}
