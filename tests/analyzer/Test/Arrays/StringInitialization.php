<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StringInitialization extends Analyzer {
    /* 2 methods */

    public function testArrays_StringInitialization01()  { $this->generic_test('Arrays/StringInitialization.01'); }
    public function testArrays_StringInitialization02()  { $this->generic_test('Arrays/StringInitialization.02'); }
}
?>