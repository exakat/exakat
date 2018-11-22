<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class StringHoldAVariable extends Analyzer {
    /* 1 methods */

    public function testType_StringHoldAVariable01()  { $this->generic_test('Type_StringHoldAVariable.01'); }
}
?>