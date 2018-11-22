<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MustCallParentConstructor extends Analyzer {
    /* 1 methods */

    public function testPhp_MustCallParentConstructor01()  { $this->generic_test('Php/MustCallParentConstructor.01'); }
}
?>