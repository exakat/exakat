<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Autoappend extends Analyzer {
    /* 1 methods */

    public function testPerformances_Autoappend01()  { $this->generic_test('Performances/Autoappend.01'); }
}
?>