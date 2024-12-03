<?php

namespace Drupal\Tests\dom_testing_selectors\Unit;

use Drupal\dom_testing_selectors\TestingSelectors;
use Drupal\Tests\dom_testing_selectors\Integration\IntegrationTestTrait;
use Drupal\Tests\UnitTestCase;

/**
 * @coversNothing
 */
class ReadMeTableExamplesTest extends UnitTestCase {

  use IntegrationTestTrait;

  public function testTableWithKeyedColumnsExample() {
    // <readme id="set_testing_selector_with_table_with_keyed_columns">
    $table = [
      '#theme' => 'table',
      '#header' => ['Name', 'Age'],
      '#rows' => [
        ['first_name' => 'Andrew', 'current_age' => 36],
      ],
    ];
    TestingSelectors::apply($table, 'members_list');
    // </readme>

    $this->dumpVariableToFile(__FUNCTION__, $table);

    $this->assertSame('members_list', $table['#attributes']['data-test']);

    $this->assertSame('Andrew', $table['#rows'][0]['data']['first_name']['data']);
    $this->assertSame('members_list_r1_first_name', $table['#rows'][0]['data']['first_name']['data-test']);
    $this->assertSame(36, $table['#rows'][0]['data']['current_age']['data']);
    $this->assertSame('members_list_r1_current_age', $table['#rows'][0]['data']['current_age']['data-test']);
  }

  public function testTableExample() {
    // <readme id="set_testing_selector_with_table">
    $table = [
      '#theme' => 'table',
      '#header' => ['Name', 'Age'],
      '#rows' => [
        ['Andrew', 36],
        ['Laurie', 34],
      ],
    ];
    TestingSelectors::apply($table, 'members_list');
    // </readme>

    $this->dumpVariableToFile(__FUNCTION__, $table);

    $this->assertSame('members_list', $table['#attributes']['data-test']);

    $this->assertSame('Andrew', $table['#rows'][0]['data'][0]['data']);
    $this->assertSame('members_list_r1_c1', $table['#rows'][0]['data'][0]['data-test']);
    $this->assertSame(36, $table['#rows'][0]['data'][1]['data']);
    $this->assertSame('members_list_r1_c2', $table['#rows'][0]['data'][1]['data-test']);

    $this->assertSame('Laurie', $table['#rows'][1]['data'][0]['data']);
    $this->assertSame('members_list_r2_c1', $table['#rows'][1]['data'][0]['data-test']);
    $this->assertSame(34, $table['#rows'][1]['data'][1]['data']);
    $this->assertSame('members_list_r2_c2', $table['#rows'][1]['data'][1]['data-test']);
  }

  public function testRowsOnlyExample() {
    // <readme id="set_testing_selector_with_rows">
    $table = [
      '#theme' => 'table',
      '#rows' => [
        ['data' => ['name' => 'Andrew', 'age' => 36]],
      ],
    ];
    TestingSelectors::apply($table['#rows'][0], 'andrew_data', 'members_table');
    // </readme>

    $this->dumpVariableToFile(__FUNCTION__, $table);
    $this->assertSame('members_table__andrew_data', $table['#rows'][0]['data-test']);
  }

  public function testCellsOnlyExample() {
    // <readme id="set_testing_selector_with_cells">
    $table = [
      '#theme' => 'table',
      '#rows' => [
        [['data' => 'Andrew'], ['data' => 36]]
      ],
    ];
    TestingSelectors::apply($table['#rows'][0][0], 'first_name', 'members_table');
    // </readme>

    $this->dumpVariableToFile(__FUNCTION__, $table);

    $this->assertSame('members_table__first_name', $table["#rows"][0][0]["data-test"]);

  }

  private function assertCells(array $table): void {
    $this->assertSame('Andrew', $table['#rows'][0][0]['data']);
    $this->assertSame('members_table__row1_cell1', $table['#rows'][0][0]['data-test']);
    $this->assertSame(36, $table['#rows'][0][1]['data']);
    $this->assertSame('members_table__row1_cell2', $table['#rows'][0][1]['data-test']);

    $this->assertSame('Laurie', $table['#rows'][1][0]['data']);
    $this->assertSame('members_table__row2_cell1', $table['#rows'][1][0]['data-test']);
    $this->assertSame(34, $table['#rows'][1][1]['data']);
    $this->assertSame('members_table__row2_cell2', $table['#rows'][1][1]['data-test']);
  }

  private function assertRows(array $table): void {
    $this->assertSame('members_table__row1', $table['#rows'][0]['data-test']);
    $this->assertSame('members_table__row2', $table['#rows'][1]['data-test']);
  }

}
