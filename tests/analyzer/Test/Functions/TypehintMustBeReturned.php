<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class TypehintMustBeReturned extends Analyzer {
    /* 1 methods */

    public function testFunctions_TypehintMustBeReturned01()  { $this->generic_test('Functions/TypehintMustBeReturned.01'); }
}
?>