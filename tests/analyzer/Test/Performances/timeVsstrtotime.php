<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class timeVsstrtotime extends Analyzer {
    /* 1 methods */

    public function testPerformances_timeVsstrtotime01()  { $this->generic_test('Performances/timeVsstrtotime.01'); }
}
?>