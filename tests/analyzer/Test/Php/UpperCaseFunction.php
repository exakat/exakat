<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UpperCaseFunction extends Analyzer {
    /* 1 methods */

    public function testPhp_UpperCaseFunction01()  { $this->generic_test('Php/UpperCaseFunction.01'); }
}
?>