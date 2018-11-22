<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Wordpress extends Analyzer {
    /* 1 methods */

    public function testVendors_Wordpress01()  { $this->generic_test('Vendors/Wordpress.01'); }
}
?>