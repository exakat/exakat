<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Fuel extends Analyzer {
    /* 1 methods */

    public function testVendors_Fuel01()  { $this->generic_test('Vendors/Fuel.01'); }
}
?>