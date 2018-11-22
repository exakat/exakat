<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IsINF extends Analyzer {
    /* 1 methods */

    public function testPhp_IsINF01()  { $this->generic_test('Php/IsINF.01'); }
}
?>