<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class TooManyParameters extends Analyzer {
    /* 3 methods */

    public function testFunctions_TooManyParameters01()  { $this->generic_test('Functions/TooManyParameters.01'); }
    public function testFunctions_TooManyParameters02()  { $this->generic_test('Functions/TooManyParameters.02'); }
    public function testFunctions_TooManyParameters03()  { $this->generic_test('Functions/TooManyParameters.03'); }
}
?>