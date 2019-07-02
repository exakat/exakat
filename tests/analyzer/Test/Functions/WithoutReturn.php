<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WithoutReturn extends Analyzer {
    /* 5 methods */

    public function testFunctions_WithoutReturn01()  { $this->generic_test('Functions_WithoutReturn.01'); }
    public function testFunctions_WithoutReturn02()  { $this->generic_test('Functions_WithoutReturn.02'); }
    public function testFunctions_WithoutReturn03()  { $this->generic_test('Functions_WithoutReturn.03'); }
    public function testFunctions_WithoutReturn04()  { $this->generic_test('Functions/WithoutReturn.04'); }
    public function testFunctions_WithoutReturn05()  { $this->generic_test('Functions/WithoutReturn.05'); }
}
?>