<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConstantUsage extends Analyzer {
    /* 10 methods */

    public function testConstants_ConstantUsage01()  { $this->generic_test('Constants_ConstantUsage.01'); }
    public function testConstants_ConstantUsage02()  { $this->generic_test('Constants_ConstantUsage.02'); }
    public function testConstants_ConstantUsage03()  { $this->generic_test('Constants_ConstantUsage.03'); }
    public function testConstants_ConstantUsage04()  { $this->generic_test('Constants_ConstantUsage.04'); }
    public function testConstants_ConstantUsage05()  { $this->generic_test('Constants_ConstantUsage.05'); }
    public function testConstants_ConstantUsage06()  { $this->generic_test('Constants/ConstantUsage.06'); }
    public function testConstants_ConstantUsage07()  { $this->generic_test('Constants/ConstantUsage.07'); }
    public function testConstants_ConstantUsage08()  { $this->generic_test('Constants/ConstantUsage.08'); }
    public function testConstants_ConstantUsage09()  { $this->generic_test('Constants/ConstantUsage.09'); }
    public function testConstants_ConstantUsage10()  { $this->generic_test('Constants/ConstantUsage.10'); }
}
?>