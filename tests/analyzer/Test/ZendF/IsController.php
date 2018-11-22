<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IsController extends Analyzer {
    /* 2 methods */

    public function testZendF_IsController01()  { $this->generic_test('ZendF/IsController.01'); }
    public function testZendF_IsController02()  { $this->generic_test('ZendF/IsController.02'); }
}
?>