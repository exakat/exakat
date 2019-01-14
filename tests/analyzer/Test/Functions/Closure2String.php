<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Closure2String extends Analyzer {
    /* 2 methods */

    public function testFunctions_Closure2String01()  { $this->generic_test('Functions/Closure2String.01'); }
    public function testFunctions_Closure2String02()  { $this->generic_test('Functions/Closure2String.02'); }
}
?>