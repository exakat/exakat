<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class VariableArguments extends Analyzer {
    /* 5 methods */

    public function testFunctions_VariableArguments01()  { $this->generic_test('Functions_VariableArguments.01'); }
    public function testFunctions_VariableArguments02()  { $this->generic_test('Functions/VariableArguments.02'); }
    public function testFunctions_VariableArguments03()  { $this->generic_test('Functions/VariableArguments.03'); }
    public function testFunctions_VariableArguments04()  { $this->generic_test('Functions/VariableArguments.04'); }
    public function testFunctions_VariableArguments05()  { $this->generic_test('Functions/VariableArguments.05'); }
}
?>