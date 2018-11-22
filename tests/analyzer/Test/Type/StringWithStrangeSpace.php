<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class StringWithStrangeSpace extends Analyzer {
    /* 1 methods */

    public function testType_StringWithStrangeSpace01()  { $this->generic_test('Type/StringWithStrangeSpace.01'); }
}
?>