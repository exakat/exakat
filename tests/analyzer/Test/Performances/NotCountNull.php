<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NotCountNull extends Analyzer {
    /* 4 methods */

    public function testPerformances_NotCountNull01()  { $this->generic_test('Performances/NotCountNull.01'); }
    public function testPerformances_NotCountNull02()  { $this->generic_test('Performances/NotCountNull.02'); }
    public function testPerformances_NotCountNull03()  { $this->generic_test('Performances/NotCountNull.03'); }
    public function testPerformances_NotCountNull04()  { $this->generic_test('Performances/NotCountNull.04'); }
}
?>