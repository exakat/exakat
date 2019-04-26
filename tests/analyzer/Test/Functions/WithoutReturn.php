<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WithoutReturn extends Analyzer {
    /* 3 methods */

    public function testFunctions_WithoutReturn01()  { $this->generic_test('Functions_WithoutReturn.01'); }
    public function testFunctions_WithoutReturn02()  { $this->generic_test('Functions_WithoutReturn.02'); }
    public function testFunctions_WithoutReturn03()  { $this->generic_test('Functions_WithoutReturn.03'); }
}
?>