<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ParameterHiding extends Analyzer {
    /* 1 methods */

    public function testFunctions_ParameterHiding01()  { $this->generic_test('Functions/ParameterHiding.01'); }
}
?>