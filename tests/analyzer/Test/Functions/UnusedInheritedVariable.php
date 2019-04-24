<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnusedInheritedVariable extends Analyzer {
    /* 1 methods */

    public function testFunctions_UnusedInheritedVariable01()  { $this->generic_test('Functions/UnusedInheritedVariable.01'); }
}
?>