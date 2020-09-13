<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MismatchParameterName extends Analyzer {
    /* 1 methods */

    public function testFunctions_MismatchParameterName01()  { $this->generic_test('Functions/MismatchParameterName.01'); }
}
?>