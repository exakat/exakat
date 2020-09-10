<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DynamicCode extends Analyzer {
    /* 1 methods */

    public function testFunctions_DynamicCode01()  { $this->generic_test('Functions/DynamicCode.01'); }
}
?>