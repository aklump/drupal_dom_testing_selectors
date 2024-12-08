<?php

namespace Drupal\Tests\dom_testing_selectors\Unit;

use Drupal\dom_testing_selectors\Handler\DrupalTableDataHandler;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use Drupal\dom_testing_selectors\Handler\DrupalTableHandler;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Drupal\dom_testing_selectors\Handler\DrupalTableHandler
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\DataTestSelector
 */
class DrupalTableHandlerTest extends TestCase {

  public static function dataForSetTestingSelectorOnElementProvider(): array {
    $tests = [];

    $rows = [];
    $rows[] = ['Andrew', 36];
    $rows[] = ['Laurie', 34];
    $tests[] = [
      [
        '#theme' => 'table',
        '#rows' => $rows,
      ],
      FALSE,
    ];

    $rows = [];
    $rows[] = ['name' => 'Andrew', 'age' => 36];
    $rows[] = ['name' => 'Laurie', 'age' => 34];
    $tests[] = [
      [
        '#theme' => 'table',
        '#rows' => $rows,
      ],
      TRUE,
    ];

    $rows = [];
    $rows[] = [
      'data' => ['Andrew', 36],
    ];
    $rows[] = [
      'data' => ['Laurie', 34],
    ];
    $tests[] = [
      [
        '#theme' => 'table',
        '#rows' => $rows,
      ],
      FALSE,
    ];

    $rows = [];
    $rows[] = [
      'data' => ['name' => 'Andrew', 'age' => 36],
    ];
    $rows[] = [
      'data' => ['name' => 'Laurie', 'age' => 34],
    ];
    $tests[] = [
      [
        '#theme' => 'table',
        '#rows' => $rows,
      ],
      TRUE,
    ];

    $rows = [];
    $rows[] = [
      ['data' => 'Andrew'],
      ['data' => 36],
    ];
    $rows[] = [
      ['data' => 'Laurie'],
      ['data' => 34],
    ];
    $tests[] = [
      [
        '#theme' => 'table',
        '#rows' => $rows,
      ],
      FALSE,
    ];

    $rows = [];
    $rows[] = [
      'data' => [
        ['data' => 'Andrew'],
        ['data' => 36],
      ],
    ];
    $rows[] = [
      'data' => [
        ['data' => 'Laurie'],
        ['data' => 34],
      ],
    ];
    $tests[] = [
      [
        '#theme' => 'table',
        '#rows' => $rows,
      ],
      FALSE,
    ];

    return $tests;
  }

  /**
   * @dataProvider dataForSetTestingSelectorOnElementProvider
   */
  public function testTableHandling(array $element, bool $assert_string_keys) {
    $marker = new DataTestSelector();
    $marker->setName('mytable');
    (new DrupalTableHandler())->setTestingSelectorOnElement($element, $marker);

    $this->assertSame('mytable', $element['#attributes']['data-test']);

    $this->assertSame('mytable_r1', $element['#rows'][0]['data-test']);
    $this->assertSame('mytable_r2', $element['#rows'][1]['data-test']);
    if ($assert_string_keys) {
      $this->assertStringKeyCellNames($element);
    }
    else {
      $this->assertNumberKeyCellNames($element);
    }
  }

  private function assertNumberKeyCellNames($element) {
    $this->assertSame('Andrew', $element['#rows'][0]['data'][0]['data']);
    $this->assertSame('mytable_r1_c1', $element['#rows'][0]['data'][0]['data-test']);
    $this->assertSame(36, $element['#rows'][0]['data'][1]['data']);
    $this->assertSame('mytable_r1_c2', $element['#rows'][0]['data'][1]['data-test']);

    $this->assertSame('Laurie', $element['#rows'][1]['data'][0]['data']);
    $this->assertSame('mytable_r2_c1', $element['#rows'][1]['data'][0]['data-test']);
    $this->assertSame(34, $element['#rows'][1]['data'][1]['data']);
    $this->assertSame('mytable_r2_c2', $element['#rows'][1]['data'][1]['data-test']);
  }

  private function assertStringKeyCellNames($element) {
    $this->assertSame('Andrew', $element['#rows'][0]['data']['name']['data']);
    $this->assertSame('mytable_r1_name', $element['#rows'][0]['data']['name']['data-test']);
    $this->assertSame(36, $element['#rows'][0]['data']['age']['data']);
    $this->assertSame('mytable_r1_age', $element['#rows'][0]['data']['age']['data-test']);

    $this->assertSame('Laurie', $element['#rows'][1]['data']['name']['data']);
    $this->assertSame('mytable_r2_name', $element['#rows'][1]['data']['name']['data-test']);
    $this->assertSame(34, $element['#rows'][1]['data']['age']['data']);
    $this->assertSame('mytable_r2_age', $element['#rows'][1]['data']['age']['data-test']);
  }

}
