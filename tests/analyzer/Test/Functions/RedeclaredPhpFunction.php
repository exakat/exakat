<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RedeclaredPhpFunction extends Analyzer {
    /* 4 methods */

    public function testFunctions_RedeclaredPhpFunction01()  { $this->generic_test('Functions_RedeclaredPhpFunction.01'); }
    public function testFunctions_RedeclaredPhpFunction02()  { $this->generic_test('Functions_RedeclaredPhpFunction.02'); }
    public function testFunctions_RedeclaredPhpFunction03()  { $this->generic_test('Functions/RedeclaredPhpFunction.03'); }
    public function testFunctions_RedeclaredPhpFunction04()  { $this->generic_test('Functions/RedeclaredPhpFunction.04'); }
}
?>