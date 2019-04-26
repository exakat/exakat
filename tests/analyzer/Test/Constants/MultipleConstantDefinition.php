<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleConstantDefinition extends Analyzer {
    /* 4 methods */

    public function testConstants_MultipleConstantDefinition01()  { $this->generic_test('Constants_MultipleConstantDefinition.01'); }
    public function testConstants_MultipleConstantDefinition02()  { $this->generic_test('Constants/MultipleConstantDefinition.02'); }
    public function testConstants_MultipleConstantDefinition03()  { $this->generic_test('Constants/MultipleConstantDefinition.03'); }
    public function testConstants_MultipleConstantDefinition04()  { $this->generic_test('Constants/MultipleConstantDefinition.04'); }
}
?>