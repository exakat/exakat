<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MissingParenthesis extends Analyzer {
    /* 1 methods */

    public function testStructures_MissingParenthesis01()  { $this->generic_test('Structures/MissingParenthesis.01'); }
}
?>