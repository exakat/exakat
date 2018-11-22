<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IsNAN extends Analyzer {
    /* 1 methods */

    public function testPhp_IsNAN01()  { $this->generic_test('Php/IsNAN.01'); }
}
?>