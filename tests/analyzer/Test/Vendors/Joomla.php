<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Joomla extends Analyzer {
    /* 1 methods */

    public function testVendors_Joomla01()  { $this->generic_test('Vendors/Joomla.01'); }
}
?>