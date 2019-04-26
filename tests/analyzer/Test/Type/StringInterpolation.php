<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StringInterpolation extends Analyzer {
    /* 2 methods */

    public function testType_StringInterpolation01()  { $this->generic_test('Type_StringInterpolation.01'); }
    public function testType_StringInterpolation02()  { $this->generic_test('Type/StringInterpolation.02'); }
}
?>