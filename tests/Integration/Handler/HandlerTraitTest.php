<?php

namespace Drupal\Tests\dom_testing_selectors\Integration\Handler;

use AKlump\DomTestingSelectors\Selector\AbstractSelector;
use AKlump\DomTestingSelectors\Selector\ClassSelector;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use Drupal\dom_testing_selectors\Handler\HandlerTrait;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class HandlerTraitTest extends TestCase {


  public static function dataFortestClassSelectorProvider(): array {
    $tests = [];
    $tests[] = [
      'foo',
      ['foo', 't-foo'],
    ];
    $tests[] = [
      ['foo'],
      ['foo', 't-foo'],
    ];
    $tests[] = [
      [],
      ['t-foo'],
    ];
    $tests[] = [
      '',
      ['t-foo'],
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestClassSelectorProvider
   */
  public function testClassSelector($current_value, array $expected) {
    $obj = new HandlerTraitTestable();
    $selector = new ClassSelector();
    $selector->setName('foo');
    $value = $obj->getAttributeValueBySelector($selector, $current_value);
    $this->assertSame($expected, $value);
  }

  public function testDataTestSelector() {
    $obj = new HandlerTraitTestable();
    $selector = new DataTestSelector();
    $selector->setName('foo');
    $value = $obj->getAttributeValueBySelector($selector, '');
    $this->assertSame('foo', $value);
    $value = $obj->getAttributeValueBySelector($selector, 'lorem');
    $this->assertSame('foo', $value);
  }

  public function testArrayValueNotUnderstoodThrows() {
    $obj = new HandlerTraitTestable();
    $selector = new Bogus12082024();
    $selector->setName('foo');
    $this->expectException(InvalidArgumentException::class);
    $obj->getAttributeValueBySelector($selector, ['bogus', 'bogus']);
  }
}

class HandlerTraitTestable {

  use HandlerTrait;
}

class Bogus12082024 extends AbstractSelector {

  public function getAttributeName(): string {
    return 'bogus12082024';
  }

}
