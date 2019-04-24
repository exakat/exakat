<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Wordpress extends Analyzer {
    /* 1 methods */

    public function testVendors_Wordpress01()  { $this->generic_test('Vendors/Wordpress.01'); }
}
?>