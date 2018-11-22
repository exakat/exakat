<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Phalcon extends Analyzer {
    /* 1 methods */

    public function testVendors_Phalcon01()  { $this->generic_test('Vendors/Phalcon.01'); }
}
?>