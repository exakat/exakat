<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Haltcompiler extends Analyzer {
    /* 1 methods */

    public function testPhp_Haltcompiler01()  { $this->generic_test('Php/Haltcompiler.01'); }
}
?>