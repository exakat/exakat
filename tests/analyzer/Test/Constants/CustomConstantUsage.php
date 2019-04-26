<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CustomConstantUsage extends Analyzer {
    /* 2 methods */

    public function testConstants_CustomConstantUsage01()  { $this->generic_test('Constants_CustomConstantUsage.01'); }
    public function testConstants_CustomConstantUsage02()  { $this->generic_test('Constants/CustomConstantUsage.02'); }
}
?>