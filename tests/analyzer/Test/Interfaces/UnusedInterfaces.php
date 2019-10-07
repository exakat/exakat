<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnusedInterfaces extends Analyzer {
    /* 3 methods */

    public function testInterfaces_UnusedInterfaces01()  { $this->generic_test('Interfaces_UnusedInterfaces.01'); }
    public function testInterfaces_UnusedInterfaces02()  { $this->generic_test('Interfaces_UnusedInterfaces.02'); }
    public function testInterfaces_UnusedInterfaces03()  { $this->generic_test('Interfaces/UnusedInterfaces.03'); }
}
?>