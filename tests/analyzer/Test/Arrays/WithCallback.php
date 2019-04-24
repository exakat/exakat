<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WithCallback extends Analyzer {
    /* 1 methods */

    public function testArrays_WithCallback01()  { $this->generic_test('Arrays/WithCallback.01'); }
}
?>