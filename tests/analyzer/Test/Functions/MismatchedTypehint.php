<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MismatchedTypehint extends Analyzer {
    /* 6 methods */

    public function testFunctions_MismatchedTypehint01()  { $this->generic_test('Functions/MismatchedTypehint.01'); }
    public function testFunctions_MismatchedTypehint02()  { $this->generic_test('Functions/MismatchedTypehint.02'); }
    public function testFunctions_MismatchedTypehint03()  { $this->generic_test('Functions/MismatchedTypehint.03'); }
    public function testFunctions_MismatchedTypehint04()  { $this->generic_test('Functions/MismatchedTypehint.04'); }
    public function testFunctions_MismatchedTypehint05()  { $this->generic_test('Functions/MismatchedTypehint.05'); }
    public function testFunctions_MismatchedTypehint06()  { $this->generic_test('Functions/MismatchedTypehint.06'); }
}
?>