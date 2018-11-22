<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MultipleSameArguments extends Analyzer {
    /* 2 methods */

    public function testFunctions_MultipleSameArguments01()  { $this->generic_test('Functions_MultipleSameArguments.01'); }
    public function testFunctions_MultipleSameArguments02()  { $this->generic_test('Functions_MultipleSameArguments.02'); }
}
?>