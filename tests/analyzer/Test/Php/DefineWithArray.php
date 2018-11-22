<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DefineWithArray extends Analyzer {
    /* 1 methods */

    public function testPhp_DefineWithArray01()  { $this->generic_test('Php/DefineWithArray.01'); }
}
?>