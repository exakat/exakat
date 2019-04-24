<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ArrayMergeInLoops extends Analyzer {
    /* 5 methods */

    public function testPerformances_ArrayMergeInLoops01()  { $this->generic_test('Performances_ArrayMergeInLoops.01'); }
    public function testPerformances_ArrayMergeInLoops02()  { $this->generic_test('Performances/ArrayMergeInLoops.02'); }
    public function testPerformances_ArrayMergeInLoops03()  { $this->generic_test('Performances/ArrayMergeInLoops.03'); }
    public function testPerformances_ArrayMergeInLoops04()  { $this->generic_test('Performances/ArrayMergeInLoops.04'); }
    public function testPerformances_ArrayMergeInLoops05()  { $this->generic_test('Performances/ArrayMergeInLoops.05'); }
}
?>