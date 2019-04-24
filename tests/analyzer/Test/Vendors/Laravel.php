<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Laravel extends Analyzer {
    /* 1 methods */

    public function testVendors_Laravel01()  { $this->generic_test('Vendors/Laravel.01'); }
}
?>