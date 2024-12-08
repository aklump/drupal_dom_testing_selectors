<?php

namespace Drupal\Tests\dom_testing_selectors\Unit\Handler;

use AKlump\DomTestingSelectors\Selector\ClassSelector;
use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use Drupal\Core\Template\Attribute;
use Drupal\dom_testing_selectors\Handler\DrupalAttributeHandler;
use Drupal\dom_testing_selectors\Handler\DrupalLinkHandler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Drupal\dom_testing_selectors\Handler\DrupalLinkHandler
 */
class DrupalLinkHandlerTest extends TestCase {

  public static function dataFortestCanHandleFalseWhenExpectedProvider(): array {
    $tests = [];
    $tests[] = [NULL];
    $tests[] = [123];
    $tests[] = ['lorem'];
    $tests[] = [['#attributes' => []]];
    $tests[] = [
      [
        '#options' => [
          'attributes' => [],
        ],
      ],
    ];
    $tests[] = [(object) ['#attributes' => []]];

    return $tests;
  }

  /**
   * @dataProvider dataFortestCanHandleFalseWhenExpectedProvider
   */
  public function testCanHandleFalseWhenExpected($element) {
    $this->assertFalse((new DrupalLinkHandler())->canHandle($element));
  }

  public function testHandlesAnAttributeObject() {
    $element = [
      '#type' => 'link',
      '#options' => [
        'attributes' => [],
      ],
    ];
    $handler = new DrupalLinkHandler();
    $this->assertTrue($handler->canHandle($element));
    $selector = new DataTestSelector();
    $selector->setName('alpha');
    $handler->setTestingSelectorOnElement($element, $selector);
    $attribute = $selector->getAttributeName();
    $this->assertArrayHasKey($attribute, $element['#options']['attributes']);
    $this->assertSame('alpha', $element['#options']['attributes'][$attribute])  ;
  }
  public function testHandlesAnAttributeObjectWithEmptyClassSelector() {
    $element = [
      '#type' => 'link',
      '#options' => [
        'attributes' => [],
      ],
    ];
    $handler = new DrupalLinkHandler();
    $this->assertTrue($handler->canHandle($element));
    $selector = new ClassSelector();
    $selector->setName('alpha');
    $handler->setTestingSelectorOnElement($element, $selector);
    $attribute = $selector->getAttributeName();
    $this->assertArrayHasKey($attribute, $element['#options']['attributes']);
    $this->assertSame(['t-alpha'], $element['#options']['attributes'][$attribute])  ;
  }
  public function testHandlesAnAttributeObjectWithExistingClassSelector() {
    $element = [
      '#type' => 'link',
      '#options' => [
        'attributes' => [
          'class' => ['lorem'],
        ],
      ],
    ];
    $handler = new DrupalLinkHandler();
    $this->assertTrue($handler->canHandle($element));
    $selector = new ClassSelector();
    $selector->setName('alpha');
    $handler->setTestingSelectorOnElement($element, $selector);
    $attribute = $selector->getAttributeName();
    $this->assertArrayHasKey($attribute, $element['#options']['attributes']);
    $this->assertSame(['lorem', 't-alpha'], $element['#options']['attributes'][$attribute])  ;
  }

}
