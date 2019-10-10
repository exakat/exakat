<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Concrete5 extends Analyzer {
    /* 1 methods */

    public function testVendors_Concrete501()  { $this->generic_test('Vendors/Concrete5.01'); }
}
?>