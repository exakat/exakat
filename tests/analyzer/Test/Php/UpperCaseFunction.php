<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UpperCaseFunction extends Analyzer {
    /* 1 methods */

    public function testPhp_UpperCaseFunction01()  { $this->generic_test('Php/UpperCaseFunction.01'); }
}
?>