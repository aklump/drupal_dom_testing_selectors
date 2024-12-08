<?php

namespace Drupal\Tests\dom_testing_selectors\Unit;

use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use AKlump\DomTestingSelectors\Tests\Unit\TestingTraits\HandlersTestTrait;
use Drupal\dom_testing_selectors\Handler\DrupalRenderArrayHandler;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Drupal\dom_testing_selectors\Handler\DrupalRenderArrayHandler
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\DataTestSelector
 */
class DrupalRenderArrayHandlerTest extends TestCase {

  public function dataFortestAppliesProvider() {
    $tests = [];
    $tests[] = [[], FALSE];
    $tests[] = [NULL, FALSE];
    $tests[] = [1, FALSE];
    $tests[] = [3.4, FALSE];
    $tests[] = ['lorem', FALSE];
    $tests[] = ['<div>foobar</div>', FALSE];
    $tests[] = [['lorem' => 'ipsum'], FALSE];
    $tests[] = [['#lorem' => 'ipsum'], TRUE];

    return $tests;
  }

  /**
   * @dataProvider dataFortestAppliesProvider
   */
  public function testApplies($subject, bool $expected) {
    $foo = new DrupalRenderArrayHandler();
    $this->assertSame($expected, $foo->canHandle($subject));
  }

  public function dataFortestHandleProvider() {
    $tests = [];
    $tests[] = [
      [
        '#type' => 'html_tag',
        '#tag' => 'div',
      ],
      'lorem',
      [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => ['data-test' => 'lorem'],
      ],
    ];
    $tests[] = [
      [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => ['class' => ['foo']],
      ],
      'lorem',
      [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => ['class' => ['foo'], 'data-test' => 'lorem'],
      ],
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestHandleProvider
   */
  public function testHandle(array $element, string $name, array $expected) {
    $marker = new DataTestSelector();
    $marker->setName($name);
    (new DrupalRenderArrayHandler())->setTestingSelectorOnElement($element, $marker);
    $this->assertSame($expected, $element);
  }

}
