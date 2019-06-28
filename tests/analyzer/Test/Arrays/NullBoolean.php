<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NullBoolean extends Analyzer {
    /* 1 methods */

    public function testArrays_NullBoolean01()  { $this->generic_test('Arrays/NullBoolean.01'); }
}
?>