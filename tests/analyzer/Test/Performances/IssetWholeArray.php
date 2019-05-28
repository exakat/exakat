<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IssetWholeArray extends Analyzer {
    /* 4 methods */

    public function testPerformances_IssetWholeArray01()  { $this->generic_test('Performances/IssetWholeArray.01'); }
    public function testPerformances_IssetWholeArray02()  { $this->generic_test('Performances/IssetWholeArray.02'); }
    public function testPerformances_IssetWholeArray03()  { $this->generic_test('Performances/IssetWholeArray.03'); }
    public function testPerformances_IssetWholeArray04()  { $this->generic_test('Performances/IssetWholeArray.04'); }
}
?>