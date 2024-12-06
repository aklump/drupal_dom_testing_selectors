<?php
// SPDX-License-Identifier: GPL-2.0-or-later
namespace Drupal\dom_testing_selectors\Factory;

use AKlump\DomTestingSelectors\Factory\AbstractHandlerFactory;
use AKlump\DomTestingSelectors\Handler\PassThroughHandler;
use AKlump\DomTestingSelectors\Handler\StringHandler;
use Drupal\dom_testing_selectors\Handler\DrupalArrayWithOneAttributeHandler;
use Drupal\dom_testing_selectors\Handler\DrupalAttributeHandler;
use Drupal\dom_testing_selectors\Handler\DrupalLinkHandler;
use Drupal\dom_testing_selectors\Handler\DrupalRenderArrayHandler;
use Drupal\dom_testing_selectors\Handler\DrupalTableDataHandler;
use Drupal\dom_testing_selectors\Handler\DrupalTableHandler;

class DrupalFactory extends AbstractHandlerFactory {

  public function __construct() {
    // The first handler to return TRUE is used, then all others skipped.
    $this->addHandler(new DrupalTableHandler());
    $this->addHandler(new DrupalLinkHandler());
    $this->addHandler(new DrupalArrayWithOneAttributeHandler());
    $this->addHandler(new DrupalAttributeHandler());
    $this->addHandler(new DrupalRenderArrayHandler());
    $this->addHandler(new DrupalTableDataHandler());
    $this->addHandler(new StringHandler());
    // Keep this last "make it a safe factory"
    // @url https://github.com/aklump/dom-testing-selectors?tab=readme-ov-file#make-it-a-safe-factory
    $this->addHandler(new PassThroughHandler());
  }

}
