<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsNotImplemented extends Analyzer {
    /* 4 methods */

    public function testInterfaces_IsNotImplemented01()  { $this->generic_test('Interfaces/IsNotImplemented.01'); }
    public function testInterfaces_IsNotImplemented02()  { $this->generic_test('Interfaces/IsNotImplemented.02'); }
    public function testInterfaces_IsNotImplemented03()  { $this->generic_test('Interfaces/IsNotImplemented.03'); }
    public function testInterfaces_IsNotImplemented04()  { $this->generic_test('Interfaces/IsNotImplemented.04'); }
}
?>