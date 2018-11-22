<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ShouldBeTypehinted extends Analyzer {
    /* 5 methods */

    public function testFunctions_ShouldBeTypehinted01()  { $this->generic_test('Functions_ShouldBeTypehinted.01'); }
    public function testFunctions_ShouldBeTypehinted02()  { $this->generic_test('Functions_ShouldBeTypehinted.02'); }
    public function testFunctions_ShouldBeTypehinted03()  { $this->generic_test('Functions_ShouldBeTypehinted.03'); }
    public function testFunctions_ShouldBeTypehinted04()  { $this->generic_test('Functions_ShouldBeTypehinted.04'); }
    public function testFunctions_ShouldBeTypehinted05()  { $this->generic_test('Functions/ShouldBeTypehinted.05'); }
}
?>