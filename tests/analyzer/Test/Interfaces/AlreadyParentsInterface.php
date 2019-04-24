<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AlreadyParentsInterface extends Analyzer {
    /* 3 methods */

    public function testInterfaces_AlreadyParentsInterface01()  { $this->generic_test('Interfaces/AlreadyParentsInterface.01'); }
    public function testInterfaces_AlreadyParentsInterface02()  { $this->generic_test('Interfaces/AlreadyParentsInterface.02'); }
    public function testInterfaces_AlreadyParentsInterface03()  { $this->generic_test('Interfaces/AlreadyParentsInterface.03'); }
}
?>