<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StringHoldAVariable extends Analyzer {
    /* 3 methods */

    public function testType_StringHoldAVariable01()  { $this->generic_test('Type_StringHoldAVariable.01'); }
    public function testType_StringHoldAVariable02()  { $this->generic_test('Type/StringHoldAVariable.02'); }
    public function testType_StringHoldAVariable03()  { $this->generic_test('Type/StringHoldAVariable.03'); }
}
?>