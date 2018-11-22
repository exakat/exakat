<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Codeigniter extends Analyzer {
    /* 1 methods */

    public function testVendors_Codeigniter01()  { $this->generic_test('Vendors/Codeigniter.01'); }
}
?>