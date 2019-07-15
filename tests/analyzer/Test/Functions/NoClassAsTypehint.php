<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoClassAsTypehint extends Analyzer {
    /* 5 methods */

    public function testFunctions_NoClassAsTypehint01()  { $this->generic_test('Functions/NoClassAsTypehint.01'); }
    public function testFunctions_NoClassAsTypehint02()  { $this->generic_test('Functions/NoClassAsTypehint.02'); }
    public function testFunctions_NoClassAsTypehint03()  { $this->generic_test('Functions/NoClassAsTypehint.03'); }
    public function testFunctions_NoClassAsTypehint04()  { $this->generic_test('Functions/NoClassAsTypehint.04'); }
    public function testFunctions_NoClassAsTypehint05()  { $this->generic_test('Functions/NoClassAsTypehint.05'); }
}
?>