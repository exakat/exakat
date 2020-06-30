<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoRealComparison extends Analyzer {
    /* 2 methods */

    public function testType_NoRealComparison01()  { $this->generic_test('Type_NoRealComparison.01'); }
    public function testType_NoRealComparison02()  { $this->generic_test('Type/NoRealComparison.02'); }
}
?>