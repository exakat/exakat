<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ShouldUseConstants extends Analyzer {
    /* 5 methods */

    public function testFunctions_ShouldUseConstants01()  { $this->generic_test('Functions_ShouldUseConstants.01'); }
    public function testFunctions_ShouldUseConstants02()  { $this->generic_test('Functions_ShouldUseConstants.02'); }
    public function testFunctions_ShouldUseConstants03()  { $this->generic_test('Functions_ShouldUseConstants.03'); }
    public function testFunctions_ShouldUseConstants04()  { $this->generic_test('Functions/ShouldUseConstants.04'); }
    public function testFunctions_ShouldUseConstants05()  { $this->generic_test('Functions/ShouldUseConstants.05'); }
}
?>