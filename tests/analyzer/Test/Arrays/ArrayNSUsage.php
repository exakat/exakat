<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ArrayNSUsage extends Analyzer {
    /* 2 methods */

    public function testArrays_ArrayNSUsage01()  { $this->generic_test('Arrays/ArrayNSUsage.01'); }
    public function testArrays_ArrayNSUsage02()  { $this->generic_test('Arrays/ArrayNSUsage.02'); }
}
?>