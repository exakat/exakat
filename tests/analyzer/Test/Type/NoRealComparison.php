<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoRealComparison extends Analyzer {
    /* 1 methods */

    public function testType_NoRealComparison01()  { $this->generic_test('Type_NoRealComparison.01'); }
}
?>