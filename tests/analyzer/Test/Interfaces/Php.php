<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Php extends Analyzer {
    /* 3 methods */

    public function testInterfaces_Php01()  { $this->generic_test('Interfaces_Php.01'); }
    public function testInterfaces_Php02()  { $this->generic_test('Interfaces_Php.02'); }
    public function testInterfaces_Php03()  { $this->generic_test('Interfaces_Php.03'); }
}
?>