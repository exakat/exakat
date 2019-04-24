<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class EmptyInterface extends Analyzer {
    /* 3 methods */

    public function testInterfaces_EmptyInterface01()  { $this->generic_test('Interfaces_EmptyInterface.01'); }
    public function testInterfaces_EmptyInterface02()  { $this->generic_test('Interfaces/EmptyInterface.02'); }
    public function testInterfaces_EmptyInterface03()  { $this->generic_test('Interfaces/EmptyInterface.03'); }
}
?>