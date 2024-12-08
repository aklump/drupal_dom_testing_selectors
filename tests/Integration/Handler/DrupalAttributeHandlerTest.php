<?php

namespace Drupal\Tests\dom_testing_selectors\Unit\Handler;

use AKlump\DomTestingSelectors\Selector\ClassSelector;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use Drupal\Core\Template\Attribute;
use Drupal\dom_testing_selectors\Handler\DrupalAttributeHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Drupal\dom_testing_selectors\Handler\DrupalAttributeHandler
 */
class DrupalAttributeHandlerTest extends TestCase {

  public static function dataFortestCanHandleFalseWhenExpectedProvider(): array {
    $tests = [];
    $tests[] = [NULL];
    $tests[] = [123];
    $tests[] = ['lorem'];
    $tests[] = [['#attributes' => []]];
    $tests[] = [(object) ['#attributes' => []]];

    return $tests;
  }

  /**
   * @dataProvider dataFortestCanHandleFalseWhenExpectedProvider
   */
  public function testCanHandleFalseWhenExpected($element) {
    $this->assertFalse((new DrupalAttributeHandler())->canHandle($element));
  }

  public function testHandlesAnAttributeObjectWithDataTestSelector() {
    $element = new Attribute();
    $handler = new DrupalAttributeHandler();
    $this->assertTrue($handler->canHandle($element));
    $selector = new DataTestSelector();
    $selector->setName('alpha');
    $handler->setTestingSelectorOnElement($element, $selector);
    $attribute = $selector->getAttributeName();
    $this->assertTrue($element->hasAttribute($attribute));
    $this->assertSame('alpha', $element->offsetGet($attribute)->value());
  }

  public function testHandlesAnAttributeObjectWithClassSelector() {
    $element = new Attribute();
    $handler = new DrupalAttributeHandler();
    $this->assertTrue($handler->canHandle($element));
    $selector = new ClassSelector();
    $selector->setName('alpha');
    $handler->setTestingSelectorOnElement($element, $selector);
    $attribute = $selector->getAttributeName();
    $this->assertTrue($element->hasAttribute($attribute));
    $this->assertSame(['t-alpha'], $element->offsetGet($attribute)->value());
  }

}
