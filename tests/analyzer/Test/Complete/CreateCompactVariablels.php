<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CreateCompactVariablels extends Analyzer {
    /* 1 methods */

    public function testComplete_CreateCompactVariablels01()  { $this->generic_test('Complete/CreateCompactVariablels.01'); }
}
?>