<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseArrowFunctions extends Analyzer {
    /* 1 methods */

    public function testFunctions_UseArrowFunctions01()  { $this->generic_test('Functions/UseArrowFunctions.01'); }
}
?>