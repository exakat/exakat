<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NullBoolean extends Analyzer {
    /* 2 methods */

    public function testArrays_NullBoolean01()  { $this->generic_test('Arrays/NullBoolean.01'); }
    public function testArrays_NullBoolean02()  { $this->generic_test('Arrays/NullBoolean.02'); }
}
?>