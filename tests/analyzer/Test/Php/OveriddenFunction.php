<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class OveriddenFunction extends Analyzer {
    /* 2 methods */

    public function testPhp_OveriddenFunction01()  { $this->generic_test('Php/OveriddenFunction.01'); }
    public function testPhp_OveriddenFunction02()  { $this->generic_test('Php/OveriddenFunction.02'); }
}
?>