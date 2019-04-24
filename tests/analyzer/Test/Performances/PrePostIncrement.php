<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PrePostIncrement extends Analyzer {
    /* 1 methods */

    public function testPerformances_PrePostIncrement01()  { $this->generic_test('Performances/PrePostIncrement.01'); }
}
?>