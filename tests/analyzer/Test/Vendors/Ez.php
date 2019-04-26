<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Ez extends Analyzer {
    /* 1 methods */

    public function testVendors_Ez01()  { $this->generic_test('Vendors/Ez.01'); }
}
?>