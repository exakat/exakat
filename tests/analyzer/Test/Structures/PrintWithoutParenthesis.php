<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PrintWithoutParenthesis extends Analyzer {
    /* 1 methods */

    public function testStructures_PrintWithoutParenthesis01()  { $this->generic_test('Structures_PrintWithoutParenthesis.01'); }
}
?>