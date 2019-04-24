<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnusedReturnedValue extends Analyzer {
    /* 2 methods */

    public function testFunctions_UnusedReturnedValue01()  { $this->generic_test('Functions/UnusedReturnedValue.01'); }
    public function testFunctions_UnusedReturnedValue02()  { $this->generic_test('Functions/UnusedReturnedValue.02'); }
}
?>