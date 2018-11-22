<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ShouldUseFunction extends Analyzer {
    /* 1 methods */

    public function testPhp_ShouldUseFunction01()  { $this->generic_test('Php/ShouldUseFunction.01'); }
}
?>