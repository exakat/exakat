<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseArraySlice extends Analyzer {
    /* 1 methods */

    public function testPerformances_UseArraySlice01()  { $this->generic_test('Performances/UseArraySlice.01'); }
}
?>