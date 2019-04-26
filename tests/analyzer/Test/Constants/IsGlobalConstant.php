<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsGlobalConstant extends Analyzer {
    /* 5 methods */

    public function testConstants_IsGlobalConstant01()  { $this->generic_test('Constants_IsGlobalConstant.01'); }
    public function testConstants_IsGlobalConstant02()  { $this->generic_test('Constants_IsGlobalConstant.02'); }
    public function testConstants_IsGlobalConstant03()  { $this->generic_test('Constants_IsGlobalConstant.03'); }
    public function testConstants_IsGlobalConstant04()  { $this->generic_test('Constants_IsGlobalConstant.04'); }
    public function testConstants_IsGlobalConstant05()  { $this->generic_test('Constants/IsGlobalConstant.05'); }
}
?>