<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Typo3 extends Analyzer {
    /* 1 methods */

    public function testVendors_Typo301()  { $this->generic_test('Vendors/Typo3.01'); }
}
?>