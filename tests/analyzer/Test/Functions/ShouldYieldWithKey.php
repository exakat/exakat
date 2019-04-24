<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldYieldWithKey extends Analyzer {
    /* 2 methods */

    public function testFunctions_ShouldYieldWithKey01()  { $this->generic_test('Functions/ShouldYieldWithKey.01'); }
    public function testFunctions_ShouldYieldWithKey02()  { $this->generic_test('Functions/ShouldYieldWithKey.02'); }
}
?>