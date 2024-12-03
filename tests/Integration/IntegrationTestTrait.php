<?php

namespace Drupal\Tests\dom_testing_selectors\Integration;

use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use Drupal;
use Drupal\dom_testing_selectors\Factory\DrupalFactory;
use Drupal\dom_testing_selectors\TestingSelectors;
use Drupal\Core\DependencyInjection\ContainerBuilder;

trait IntegrationTestTrait {

  public function setUp(): void {
    $container = new ContainerBuilder();
    Drupal::setContainer($container);

    $container->set('dom_testing_selectors.factory', new DrupalFactory());
    $container->set('dom_testing_selectors.selector', new DataTestSelector());
  }

  /**
   * @param string $filename
   * @param $data
   *
   * @return void
   *
   * @code
   * $this->dumpVariableToFile(__FUNCTION__, $html);
   * @endcode
   */
  public function dumpVariableToFile(string $filename, $data) {
    $filename = preg_replace('#[A-Z]#', '_$0', $filename);
    $filename = trim(strtolower($filename), '_');
    $filename = preg_replace('#^test_#', 'readme_', $filename);
    $path = __DIR__ . '/output/' . $filename . '.txt';
    if (!file_exists(dirname($path))) {
      mkdir(dirname($path), 0755, TRUE);
    }
    file_put_contents($path, print_r($data, TRUE));
  }

}
