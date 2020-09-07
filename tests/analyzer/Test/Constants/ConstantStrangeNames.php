<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConstantStrangeNames extends Analyzer {
    /* 3 methods */

    public function testConstants_ConstantStrangeNames01()  { $this->generic_test('Constants_ConstantStrangeNames.01'); }
    public function testConstants_ConstantStrangeNames02()  { $this->generic_test('Constants_ConstantStrangeNames.02'); }
    public function testConstants_ConstantStrangeNames03()  { $this->generic_test('Constants/ConstantStrangeNames.03'); }
}
?>