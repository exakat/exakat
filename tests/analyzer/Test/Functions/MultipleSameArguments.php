<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleSameArguments extends Analyzer {
    /* 3 methods */

    public function testFunctions_MultipleSameArguments01()  { $this->generic_test('Functions_MultipleSameArguments.01'); }
    public function testFunctions_MultipleSameArguments02()  { $this->generic_test('Functions_MultipleSameArguments.02'); }
    public function testFunctions_MultipleSameArguments03()  { $this->generic_test('Functions/MultipleSameArguments.03'); }
}
?>