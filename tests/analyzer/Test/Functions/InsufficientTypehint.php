<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class InsufficientTypehint extends Analyzer {
    /* 3 methods */

    public function testFunctions_InsufficientTypehint01()  { $this->generic_test('Functions/InsufficientTypehint.01'); }
    public function testFunctions_InsufficientTypehint02()  { $this->generic_test('Functions/InsufficientTypehint.02'); }
    public function testFunctions_InsufficientTypehint03()  { $this->generic_test('Functions/InsufficientTypehint.03'); }
}
?>