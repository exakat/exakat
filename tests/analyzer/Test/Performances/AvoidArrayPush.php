<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AvoidArrayPush extends Analyzer {
    /* 1 methods */

    public function testPerformances_AvoidArrayPush01()  { $this->generic_test('Performances/AvoidArrayPush.01'); }
}
?>