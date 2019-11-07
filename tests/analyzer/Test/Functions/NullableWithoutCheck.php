<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NullableWithoutCheck extends Analyzer {
    /* 1 methods */

    public function testFunctions_NullableWithoutCheck01()  { $this->generic_test('Functions/NullableWithoutCheck.01'); }
}
?>