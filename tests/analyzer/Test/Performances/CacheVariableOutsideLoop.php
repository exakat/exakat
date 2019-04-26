<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CacheVariableOutsideLoop extends Analyzer {
    /* 2 methods */

    public function testPerformances_CacheVariableOutsideLoop01()  { $this->generic_test('Performances/CacheVariableOutsideLoop.01'); }
    public function testPerformances_CacheVariableOutsideLoop02()  { $this->generic_test('Performances/CacheVariableOutsideLoop.02'); }
}
?>