<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NegativeStart extends Analyzer {
    /* 2 methods */

    public function testArrays_NegativeStart01()  { $this->generic_test('Arrays/NegativeStart.01'); }
    public function testArrays_NegativeStart02()  { $this->generic_test('Arrays/NegativeStart.02'); }
}
?>