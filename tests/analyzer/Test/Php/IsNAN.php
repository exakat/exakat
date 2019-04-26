<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsNAN extends Analyzer {
    /* 1 methods */

    public function testPhp_IsNAN01()  { $this->generic_test('Php/IsNAN.01'); }
}
?>