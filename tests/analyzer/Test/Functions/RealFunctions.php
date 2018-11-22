<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class RealFunctions extends Analyzer {
    /* 1 methods */

    public function testFunctions_RealFunctions01()  { $this->generic_test('Functions/RealFunctions.01'); }
}
?>