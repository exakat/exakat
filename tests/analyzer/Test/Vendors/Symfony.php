<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Symfony extends Analyzer {
    /* 1 methods */

    public function testVendors_Symfony01()  { $this->generic_test('Vendors/Symfony.01'); }
}
?>