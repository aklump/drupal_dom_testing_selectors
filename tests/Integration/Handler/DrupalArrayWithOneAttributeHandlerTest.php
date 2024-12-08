<?php

namespace Drupal\Tests\dom_testing_selectors\Unit\Handler;

use AKlump\DomTestingSelectors\Selector\ClassSelector;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use Drupal\Core\Template\Attribute;
use Drupal\dom_testing_selectors\Handler\DrupalArrayWithOneAttributeHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Drupal\dom_testing_selectors\Handler\DrupalArrayWithOneAttributeHandler()
 */
class DrupalArrayWithOneAttributeHandlerTest extends TestCase {

  public static function dataFortestCanHandleFalseWhenExpectedProvider(): array {
    $tests = [];
    $tests[] = [NULL];
    $tests[] = [123];
    $tests[] = ['lorem'];
    $tests[] = [['#attributes' => []]];
    $tests[] = [(object) ['#attributes' => []]];
    $tests[] = [new Attribute()];

    return $tests;
  }

  /**
   * @dataProvider dataFortestCanHandleFalseWhenExpectedProvider
   */
  public function testCanHandleFalseWhenExpected($element) {
    $this->assertFalse((new DrupalArrayWithOneAttributeHandler())->canHandle($element));
  }

  public function testHandlesAnAttributeObjectWithDataTestSelector() {
    $element = ['foobar' => new Attribute()];
    $handler = new DrupalArrayWithOneAttributeHandler();
    $this->assertTrue($handler->canHandle($element));
    $selector = new DataTestSelector();
    $selector->setName('alpha');
    $handler->setTestingSelectorOnElement($element, $selector);
    $attribute = $selector->getAttributeName();
    $this->assertTrue($element['foobar']->hasAttribute($attribute));
    $this->assertSame('alpha', $element['foobar']->offsetGet($attribute)->value());
  }
  public function testHandlesAnAttributeObjectWithClassSelector() {
    $element = ['foobar' => new Attribute()];
    $handler = new DrupalArrayWithOneAttributeHandler();
    $this->assertTrue($handler->canHandle($element));
    $selector = new ClassSelector();
    $selector->setName('alpha');
    $handler->setTestingSelectorOnElement($element, $selector);
    $attribute = $selector->getAttributeName();
    $this->assertTrue($element['foobar']->hasAttribute($attribute));
    $this->assertSame(['t-alpha'], $element['foobar']->offsetGet($attribute)->value());
  }
}
