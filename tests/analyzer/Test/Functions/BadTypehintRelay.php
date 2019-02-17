<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class BadTypehintRelay extends Analyzer {
    /* 1 methods */

    public function testFunctions_BadTypehintRelay01()  { $this->generic_test('Functions/BadTypehintRelay.01'); }
}
?>