<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class InterfaceUsage extends Analyzer {
    /* 7 methods */

    public function testInterfaces_InterfaceUsage01()  { $this->generic_test('Interfaces_InterfaceUsage.01'); }
    public function testInterfaces_InterfaceUsage02()  { $this->generic_test('Interfaces_InterfaceUsage.02'); }
    public function testInterfaces_InterfaceUsage03()  { $this->generic_test('Interfaces/InterfaceUsage.03'); }
    public function testInterfaces_InterfaceUsage04()  { $this->generic_test('Interfaces/InterfaceUsage.04'); }
    public function testInterfaces_InterfaceUsage05()  { $this->generic_test('Interfaces/InterfaceUsage.05'); }
    public function testInterfaces_InterfaceUsage06()  { $this->generic_test('Interfaces/InterfaceUsage.06'); }
    public function testInterfaces_InterfaceUsage07()  { $this->generic_test('Interfaces/InterfaceUsage.07'); }
}
?>