<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UndefinedVariable extends Analyzer {
    /* 4 methods */

    public function testVariables_UndefinedVariable01()  { $this->generic_test('Variables/UndefinedVariable.01'); }
    public function testVariables_UndefinedVariable02()  { $this->generic_test('Variables/UndefinedVariable.02'); }
    public function testVariables_UndefinedVariable03()  { $this->generic_test('Variables/UndefinedVariable.03'); }
    public function testVariables_UndefinedVariable04()  { $this->generic_test('Variables/UndefinedVariable.04'); }
}
?>