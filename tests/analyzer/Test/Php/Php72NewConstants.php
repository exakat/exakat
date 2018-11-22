<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Php72NewConstants extends Analyzer {
    /* 2 methods */

    public function testPhp_Php72NewConstants01()  { $this->generic_test('Php/Php72NewConstants.01'); }
    public function testPhp_Php72NewConstants02()  { $this->generic_test('Php/Php72NewConstants.02'); }
}
?>