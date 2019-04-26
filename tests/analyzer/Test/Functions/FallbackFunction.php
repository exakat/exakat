<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FallbackFunction extends Analyzer {
    /* 2 methods */

    public function testFunctions_FallbackFunction01()  { $this->generic_test('Functions/FallbackFunction.01'); }
    public function testFunctions_FallbackFunction02()  { $this->generic_test('Functions/FallbackFunction.02'); }
}
?>