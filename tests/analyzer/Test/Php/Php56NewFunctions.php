<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Php56NewFunctions extends Analyzer {
    /* 2 methods */

    public function testPhp_Php56NewFunctions01()  { $this->generic_test('Php/Php56NewFunctions.01'); }
    public function testPhp_Php56NewFunctions02()  { $this->generic_test('Php/Php56NewFunctions.02'); }
}
?>