<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class VariableNonascii extends Analyzer {
    /* 2 methods */

    public function testVariables_VariableNonascii01()  { $this->generic_test('Variables_VariableNonascii.01'); }
    public function testVariables_VariableNonascii02()  { $this->generic_test('Variables/VariableNonascii.02'); }
}
?>