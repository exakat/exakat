<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoBooleanAsDefault extends Analyzer {
    /* 2 methods */

    public function testFunctions_NoBooleanAsDefault01()  { $this->generic_test('Functions/NoBooleanAsDefault.01'); }
    public function testFunctions_NoBooleanAsDefault02()  { $this->generic_test('Functions/NoBooleanAsDefault.02'); }
}
?>