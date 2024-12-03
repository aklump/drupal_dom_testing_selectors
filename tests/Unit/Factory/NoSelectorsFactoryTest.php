<?php

namespace Drupal\Tests\dom_testing_selectors\Factory;

use AKlump\DomTestingSelectors\Selector\DataTestSelector;
use Drupal\dom_testing_selectors\Factory\NoSelectorsFactory;
use PHPUnit\Framework\TestCase;

class NoSelectorsFactoryTest extends TestCase {

  public function testGetHandlerReturnsAHandlerThatMakesNoChange() {
    $factory = new NoSelectorsFactory();
    $element = '<div>foo</div>';
    $handler = $factory->getHandler($element);

    $this->assertTrue($handler->canHandle($element));

    $before = $element;
    $handler->setTestingSelectorOnElement($element, (new DataTestSelector())->setName('foo'));
    $this->assertSame($before, $element);
  }
}
