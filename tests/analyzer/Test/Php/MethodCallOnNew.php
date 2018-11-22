<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MethodCallOnNew extends Analyzer {
    /* 1 methods */

    public function testPhp_MethodCallOnNew01()  { $this->generic_test('Php/MethodCallOnNew.01'); }
}
?>