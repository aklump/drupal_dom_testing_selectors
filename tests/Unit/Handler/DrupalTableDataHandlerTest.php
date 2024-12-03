<?php

namespace Drupal\Tests\dom_testing_selectors\Unit;

use Drupal\dom_testing_selectors\Handler\DrupalTableDataHandler;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AKlump\DomTestingSelectors\Handler\DrupalTableDataHandler()
 * @uses   \AKlump\DomTestingSelectors\Selector\AbstractSelector
 * @uses   \AKlump\DomTestingSelectors\Selector\DataTestSelector
 */
class DrupalTableDataHandlerTest extends TestCase {

  public function dataFortestAppliesProvider() {
    $tests = [];

    // Neither cells nor rows.
    $tests[] = [[], FALSE];
    $tests[] = [NULL, FALSE];
    $tests[] = [1, FALSE];
    $tests[] = [3.4, FALSE];
    $tests[] = ['lorem', FALSE];
    $tests[] = ['<div>foobar</div>', FALSE];
    $tests[] = [['lorem' => 'ipsum'], FALSE];
    $tests[] = [['#lorem' => 'ipsum'], FALSE];

    // Cell format.
    $tests[] = [['data' => 'ipsum'], TRUE];

    // Row format.
    $tests[] = [
      [
        // 3 scalar cells.
        'data' => ['do', 're', 'mi'],
      ],
      TRUE,
    ];
    $tests[] = [
      [
        // 2 named cells.
        'data' => ['name' => 'Andrew', 'age' => 36],
      ],
      TRUE,
    ];
    $tests[] = [
      [
        // 2 data-keyed, cells
        'data' => [
          ['data' => 'foo', 'class' => 'foo'],
          ['data' => 'bar', 'class' => 'bar'],
        ],
        'class' => 'foobar',
      ],
      TRUE,
    ];


    return $tests;
  }

  /**
   * @dataProvider dataFortestAppliesProvider
   */
  public function testApplies($subject, bool $expected) {
    $foo = new DrupalTableDataHandler();
    $this->assertSame($expected, $foo->canHandle($subject));
  }

  /**
   * @dataProvider dataFortestAppliesProvider
   */
  public function testAppliesThrowsWhenBadSubject($subject, bool $expected) {
    $foo = new DrupalTableDataHandler();
    if (TRUE === $expected) {
      $this->assertTrue(TRUE);

      return;
    }

    $this->expectException(InvalidArgumentException::class);
    $foo->setTestingSelectorOnElement($expected, new DataTestSelector());
  }

  public function dataFortestHandleProvider() {
    $tests = [];
    $tests[] = [
      [
        'data' => ['name' => 'Andrew', 'age' => 36],
      ],
      'row_0',
      [
        'data' => ['name' => 'Andrew', 'age' => 36],
        'data-test' => 'row_0',
      ],
    ];
    $tests[] = [
      [
        'data' => [],
      ],
      'lorem',
      [
        'data' => [],
        'data-test' => 'lorem',
      ],
    ];
    $tests[] = [
      [
        'data' => [],
        'class' => ['foo'],
      ],
      'lorem',
      [
        'data' => [],
        'class' => ['foo'],
        'data-test' => 'lorem',
      ],
    ];

    $tests[] = [
      [
        // 2 data-keyed, cells
        'data' => [
          ['data' => 'foo', 'class' => 'foo'],
          ['data' => 'bar', 'class' => 'bar'],
        ],
        'class' => 'foobar',
      ],
      'dolphin',
      [
        // 2 data-keyed, cells
        'data' => [
          ['data' => 'foo', 'class' => 'foo'],
          ['data' => 'bar', 'class' => 'bar'],
        ],
        'class' => 'foobar',
        'data-test' => 'dolphin',
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
    (new DrupalTableDataHandler())->setTestingSelectorOnElement($element, $marker);
    $this->assertSame($expected, $element);
  }

}
