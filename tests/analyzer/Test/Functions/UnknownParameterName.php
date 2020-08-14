<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnknownParameterName extends Analyzer {
    /* 2 methods */

    public function testFunctions_UnknownParameterName01()  { $this->generic_test('Functions/UnknownParameterName.01'); }
    public function testFunctions_UnknownParameterName02()  { $this->generic_test('Functions/UnknownParameterName.02'); }
}
?>