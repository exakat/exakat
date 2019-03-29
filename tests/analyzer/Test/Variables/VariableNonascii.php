<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class VariableNonascii extends Analyzer {
    /* 3 methods */

    public function testVariables_VariableNonascii01()  { $this->generic_test('Variables_VariableNonascii.01'); }
    public function testVariables_VariableNonascii02()  { $this->generic_test('Variables/VariableNonascii.02'); }
    public function testVariables_VariableNonascii03()  { $this->generic_test('Variables/VariableNonascii.03'); }
}
?>