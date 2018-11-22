<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class VariableOneLetter extends Analyzer {
    /* 2 methods */

    public function testVariables_VariableOneLetter01()  { $this->generic_test('Variables_VariableOneLetter.01'); }
    public function testVariables_VariableOneLetter02()  { $this->generic_test('Variables_VariableOneLetter.02'); }
}
?>