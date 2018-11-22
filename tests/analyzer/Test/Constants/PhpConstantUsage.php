<?php

namespace Test\Constants;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PhpConstantUsage extends Analyzer {
    /* 1 methods */

    public function testConstants_PhpConstantUsage01()  { $this->generic_test('Constants_PhpConstantUsage.01'); }
}
?>