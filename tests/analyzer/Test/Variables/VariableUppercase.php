<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class VariableUppercase extends Analyzer {
    /* 3 methods */

    public function testVariables_VariableUppercase01()  { $this->generic_test('Variables_VariableUppercase.01'); }
    public function testVariables_VariableUppercase02()  { $this->generic_test('Variables_VariableUppercase.02'); }
    public function testVariables_VariableUppercase03()  { $this->generic_test('Variables_VariableUppercase.03'); }
}
?>