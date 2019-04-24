<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MagicConstantUsage extends Analyzer {
    /* 2 methods */

    public function testConstants_MagicConstantUsage01()  { $this->generic_test('Constants_MagicConstantUsage.01'); }
    public function testConstants_MagicConstantUsage02()  { $this->generic_test('Constants_MagicConstantUsage.02'); }
}
?>