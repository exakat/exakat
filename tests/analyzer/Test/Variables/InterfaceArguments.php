<?php

namespace Test\Variables;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class InterfaceArguments extends Analyzer {
    /* 3 methods */

    public function testVariables_InterfaceArguments01()  { $this->generic_test('Variables_InterfaceArguments.01'); }
    public function testVariables_InterfaceArguments02()  { $this->generic_test('Variables_InterfaceArguments.02'); }
    public function testVariables_InterfaceArguments03()  { $this->generic_test('Variables/InterfaceArguments.03'); }
}
?>