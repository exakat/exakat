<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessInterfaces extends Analyzer {
    /* 3 methods */

    public function testInterfaces_UselessInterfaces01()  { $this->generic_test('Interfaces_UselessInterfaces.01'); }
    public function testInterfaces_UselessInterfaces02()  { $this->generic_test('Interfaces/UselessInterfaces.02'); }
    public function testInterfaces_UselessInterfaces03()  { $this->generic_test('Interfaces/UselessInterfaces.03'); }
}
?>