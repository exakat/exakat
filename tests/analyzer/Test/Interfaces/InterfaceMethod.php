<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class InterfaceMethod extends Analyzer {
    /* 1 methods */

    public function testInterfaces_InterfaceMethod01()  { $this->generic_test('Interfaces_InterfaceMethod.01'); }
}
?>