<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OnlyVariablePassedByReference extends Analyzer {
    /* 5 methods */

    public function testFunctions_OnlyVariablePassedByReference01()  { $this->generic_test('Functions/OnlyVariablePassedByReference.01'); }
    public function testFunctions_OnlyVariablePassedByReference02()  { $this->generic_test('Functions/OnlyVariablePassedByReference.02'); }
    public function testFunctions_OnlyVariablePassedByReference03()  { $this->generic_test('Functions/OnlyVariablePassedByReference.03'); }
    public function testFunctions_OnlyVariablePassedByReference04()  { $this->generic_test('Functions/OnlyVariablePassedByReference.04'); }
    public function testFunctions_OnlyVariablePassedByReference05()  { $this->generic_test('Functions/OnlyVariablePassedByReference.05'); }
}
?>