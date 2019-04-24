<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RegexOnCollector extends Analyzer {
    /* 1 methods */

    public function testPerformances_RegexOnCollector01()  { $this->generic_test('Performances/RegexOnCollector.01'); }
}
?>