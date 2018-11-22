<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AvoidArrayPush extends Analyzer {
    /* 1 methods */

    public function testPerformances_AvoidArrayPush01()  { $this->generic_test('Performances/AvoidArrayPush.01'); }
}
?>