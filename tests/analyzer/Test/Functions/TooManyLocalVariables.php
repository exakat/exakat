<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TooManyLocalVariables extends Analyzer {
    /* 3 methods */

    public function testFunctions_TooManyLocalVariables01()  { $this->generic_test('Functions/TooManyLocalVariables.01'); }
    public function testFunctions_TooManyLocalVariables02()  { $this->generic_test('Functions/TooManyLocalVariables.02'); }
    public function testFunctions_TooManyLocalVariables03()  { $this->generic_test('Functions/TooManyLocalVariables.03'); }
}
?>