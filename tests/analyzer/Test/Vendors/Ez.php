<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Ez extends Analyzer {
    /* 1 methods */

    public function testVendors_Ez01()  { $this->generic_test('Vendors/Ez.01'); }
}
?>