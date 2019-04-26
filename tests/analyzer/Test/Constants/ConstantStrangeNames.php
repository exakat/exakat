<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConstantStrangeNames extends Analyzer {
    /* 2 methods */

    public function testConstants_ConstantStrangeNames01()  { $this->generic_test('Constants_ConstantStrangeNames.01'); }
    public function testConstants_ConstantStrangeNames02()  { $this->generic_test('Constants_ConstantStrangeNames.02'); }
}
?>