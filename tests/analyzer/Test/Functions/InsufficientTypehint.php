<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class InsufficientTypehint extends Analyzer {
    /* 8 methods */

    public function testFunctions_InsufficientTypehint01()  { $this->generic_test('Functions/InsufficientTypehint.01'); }
    public function testFunctions_InsufficientTypehint02()  { $this->generic_test('Functions/InsufficientTypehint.02'); }
    public function testFunctions_InsufficientTypehint03()  { $this->generic_test('Functions/InsufficientTypehint.03'); }
    public function testFunctions_InsufficientTypehint04()  { $this->generic_test('Functions/InsufficientTypehint.04'); }
    public function testFunctions_InsufficientTypehint05()  { $this->generic_test('Functions/InsufficientTypehint.05'); }
    public function testFunctions_InsufficientTypehint06()  { $this->generic_test('Functions/InsufficientTypehint.06'); }
    public function testFunctions_InsufficientTypehint07()  { $this->generic_test('Functions/InsufficientTypehint.07'); }
    public function testFunctions_InsufficientTypehint08()  { $this->generic_test('Functions/InsufficientTypehint.08'); }
}
?>