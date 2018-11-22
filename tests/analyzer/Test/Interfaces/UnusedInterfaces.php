<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnusedInterfaces extends Analyzer {
    /* 2 methods */

    public function testInterfaces_UnusedInterfaces01()  { $this->generic_test('Interfaces_UnusedInterfaces.01'); }
    public function testInterfaces_UnusedInterfaces02()  { $this->generic_test('Interfaces_UnusedInterfaces.02'); }
}
?>