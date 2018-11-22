<?php

namespace Test\Vendors;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Yii extends Analyzer {
    /* 1 methods */

    public function testVendors_Yii01()  { $this->generic_test('Vendors/Yii.01'); }
}
?>