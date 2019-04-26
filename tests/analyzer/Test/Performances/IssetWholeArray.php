<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IssetWholeArray extends Analyzer {
    /* 2 methods */

    public function testPerformances_IssetWholeArray01()  { $this->generic_test('Performances/IssetWholeArray.01'); }
    public function testPerformances_IssetWholeArray02()  { $this->generic_test('Performances/IssetWholeArray.02'); }
}
?>