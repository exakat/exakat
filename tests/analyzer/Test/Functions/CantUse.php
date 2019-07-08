<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CantUse extends Analyzer {
    /* 1 methods */

    public function testFunctions_CantUse01()  { $this->generic_test('Functions/CantUse.01'); }
}
?>