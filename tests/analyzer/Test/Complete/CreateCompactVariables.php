<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CreateCompactVariables extends Analyzer {
    /* 1 methods */

    public function testComplete_CreateCompactVariables01()  { $this->generic_test('Complete/CreateCompactVariables.01'); }
}
?>