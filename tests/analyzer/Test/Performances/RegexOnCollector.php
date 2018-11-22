<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class RegexOnCollector extends Analyzer {
    /* 1 methods */

    public function testPerformances_RegexOnCollector01()  { $this->generic_test('Performances/RegexOnCollector.01'); }
}
?>