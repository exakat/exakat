<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IsView extends Analyzer {
    /* 1 methods */

    public function testZendF_IsView01()  { $this->generic_test('ZendF/IsView.01'); }
}
?>