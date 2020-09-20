<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MismatchParameterAndType extends Analyzer {
    /* 1 methods */

    public function testFunctions_MismatchParameterAndType01()  { $this->generic_test('Functions/MismatchParameterAndType.01'); }
}
?>