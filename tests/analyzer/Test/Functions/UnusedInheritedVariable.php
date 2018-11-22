<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnusedInheritedVariable extends Analyzer {
    /* 1 methods */

    public function testFunctions_UnusedInheritedVariable01()  { $this->generic_test('Functions/UnusedInheritedVariable.01'); }
}
?>