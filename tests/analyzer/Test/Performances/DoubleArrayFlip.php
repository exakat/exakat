<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DoubleArrayFlip extends Analyzer {
    /* 1 methods */

    public function testPerformances_DoubleArrayFlip01()  { $this->generic_test('Performances/DoubleArrayFlip.01'); }
}
?>