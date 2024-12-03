<?php

namespace AKlump\Knowledge\User;

use AKlump\Knowledge\Events\GetVariables;

class GetReadMeCode {

  use CodeExtractionTrait;

  public function __invoke(GetVariables $event) {
    $book_path = $event->getPathToSource();
    $readme_test_files = glob(dirname($book_path) . '/tests/Integration/*Test.php');
    $snippets = [];
    foreach ($readme_test_files as $readme_test_file) {
      $content = file_get_contents($readme_test_file);
      $snippets = array_merge($snippets, $this->extractReadMeSnippets($content));
    }
    if (empty($snippets)) {
      return;
    }
    foreach ($snippets as $id => $code) {
      $event->setVariable('readme_' . $id, $code);
    }

    $outputs = glob(dirname($book_path) . '/tests/Integration/output/*.txt');
    foreach ($outputs as $output) {
      $content = file_get_contents($output);
      $var_name = pathinfo($output, PATHINFO_FILENAME);
      $event->setVariable($var_name, $content);
    }
  }

}
