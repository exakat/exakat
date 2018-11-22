<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MismatchedTypehint extends Analyzer {
    /* 4 methods */

    public function testFunctions_MismatchedTypehint01()  { $this->generic_test('Functions/MismatchedTypehint.01'); }
    public function testFunctions_MismatchedTypehint02()  { $this->generic_test('Functions/MismatchedTypehint.02'); }
    public function testFunctions_MismatchedTypehint03()  { $this->generic_test('Functions/MismatchedTypehint.03'); }
    public function testFunctions_MismatchedTypehint04()  { $this->generic_test('Functions/MismatchedTypehint.04'); }
}
?>