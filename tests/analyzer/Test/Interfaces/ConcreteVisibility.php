<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConcreteVisibility extends Analyzer {
    /* 1 methods */

    public function testInterfaces_ConcreteVisibility01()  { $this->generic_test('Interfaces_ConcreteVisibility.01'); }
}
?>