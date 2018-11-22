<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AlreadyParentsInterface extends Analyzer {
    /* 2 methods */

    public function testInterfaces_AlreadyParentsInterface01()  { $this->generic_test('Interfaces/AlreadyParentsInterface.01'); }
    public function testInterfaces_AlreadyParentsInterface02()  { $this->generic_test('Interfaces/AlreadyParentsInterface.02'); }
}
?>