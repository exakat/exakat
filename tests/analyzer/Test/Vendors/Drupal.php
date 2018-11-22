<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Drupal extends Analyzer {
    /* 1 methods */

    public function testVendors_Drupal01()  { $this->generic_test('Vendors/Drupal.01'); }
}
?>