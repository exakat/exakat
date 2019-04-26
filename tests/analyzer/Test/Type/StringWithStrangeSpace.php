<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StringWithStrangeSpace extends Analyzer {
    /* 1 methods */

    public function testType_StringWithStrangeSpace01()  { $this->generic_test('Type/StringWithStrangeSpace.01'); }
}
?>