<?php
// SPDX-License-Identifier: GPL-2.0-or-later
namespace Drupal\dom_testing_selectors\Factory;

use AKlump\DomTestingSelectors\Factory\AbstractHandlerFactory;
use AKlump\DomTestingSelectors\Handler\PassThroughHandler;
use AKlump\DomTestingSelectors\Handler\StringHandler;
use Drupal\dom_testing_selectors\Handler\DrupalRenderArrayHandler;
use Drupal\dom_testing_selectors\Handler\DrupalTableDataHandler;
use Drupal\dom_testing_selectors\Handler\DrupalTableHandler;

/**
 * This class may be swapped into the dom_testing_selectors.factory service to
 * disable the testing selectors from rendering.  See README.md for info.
 */
class NoSelectorsFactory extends AbstractHandlerFactory {

  public function __construct() {
    $this->addHandler(new PassThroughHandler());
  }

}
