<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Fuel extends Analyzer {
    /* 1 methods */

    public function testVendors_Fuel01()  { $this->generic_test('Vendors/Fuel.01'); }
}
?>