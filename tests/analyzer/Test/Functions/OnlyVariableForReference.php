<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class OnlyVariableForReference extends Analyzer {
    /* 4 methods */

    public function testFunctions_OnlyVariableForReference01()  { $this->generic_test('Functions/OnlyVariableForReference.01'); }
    public function testFunctions_OnlyVariableForReference02()  { $this->generic_test('Functions/OnlyVariableForReference.02'); }
    public function testFunctions_OnlyVariableForReference03()  { $this->generic_test('Functions/OnlyVariableForReference.03'); }
    public function testFunctions_OnlyVariableForReference04()  { $this->generic_test('Functions/OnlyVariableForReference.04'); }
}
?>