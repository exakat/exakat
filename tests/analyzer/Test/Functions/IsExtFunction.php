<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsExtFunction extends Analyzer {
    /* 2 methods */

    public function testFunctions_IsExtFunction01()  { $this->generic_test('Functions_IsExtFunction.01'); }
    public function testFunctions_IsExtFunction02()  { $this->generic_test('Functions_IsExtFunction.02'); }
}
?>