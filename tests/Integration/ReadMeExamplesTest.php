<?php

namespace Drupal\Tests\dom_testing_selectors\Integration;

use Drupal\dom_testing_selectors\TestingSelectors;
use Drupal\Tests\UnitTestCase;

class ReadMeExamplesTest extends UnitTestCase {

  use IntegrationTestTrait;

  public function testTestingSelectorExample() {

    // <readme id="set_testing_selector">
    $render_element = [
      '#type' => 'tag',
      '#tag' => 'h1',
    ];

    \Drupal\dom_testing_selectors\TestingSelectors::apply($render_element, 'page_title');
    // </readme>

    $this->dumpVariableToFile(__FUNCTION__, $render_element);

    $this->assertArrayHasKey('data-test', $render_element['#attributes']);
    $this->assertSame('page_title', $render_element['#attributes']['data-test']);
  }

  public function testTestingSelectorWithStringExample() {

    // <readme id="set_testing_selector_with_string">
    $html = '<h1>Lorem ipsum</h1>';
    TestingSelectors::apply($html, 'page_title');
    // </readme>

    $this->dumpVariableToFile(__FUNCTION__, $html);

    $this->assertStringContainsString('<h1 data-test="page_title">', $html);
  }

  public function testTestingSelectorWithGroupsExample() {

    // <readme id="set_testing_selector_with_group">
    $render_elements = [];

    /** @var string $group The test group for the markers. */
    $group = 'typeface';

    $render_element = [
      '#type' => 'tag',
      '#tag' => 'h1',
    ];
    TestingSelectors::apply($render_element, 'page_title', $group);
    $render_elements[] = $render_element;

    $render_element = [
      '#type' => 'tag',
      '#tag' => 'h2',
    ];
    TestingSelectors::apply($render_element, 'page_subtitle', $group);
    $render_elements[] = $render_element;
    // </readme>

    $this->dumpVariableToFile(__FUNCTION__, $render_elements);

    $this->assertArrayHasKey('data-test', $render_elements[0]['#attributes']);
    $this->assertSame('typeface__page_title', $render_elements[0]['#attributes']['data-test']);

    $this->assertArrayHasKey('data-test', $render_elements[1]['#attributes']);
    $this->assertSame('typeface__page_subtitle', $render_elements[1]['#attributes']['data-test']);
  }

}
