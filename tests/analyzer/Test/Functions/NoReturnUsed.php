<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoReturnUsed extends Analyzer {
    /* 5 methods */

    public function testFunctions_NoReturnUsed01()  { $this->generic_test('Functions/NoReturnUsed.01'); }
    public function testFunctions_NoReturnUsed02()  { $this->generic_test('Functions/NoReturnUsed.02'); }
    public function testFunctions_NoReturnUsed03()  { $this->generic_test('Functions/NoReturnUsed.03'); }
    public function testFunctions_NoReturnUsed04()  { $this->generic_test('Functions/NoReturnUsed.04'); }
    public function testFunctions_NoReturnUsed05()  { $this->generic_test('Functions/NoReturnUsed.05'); }
}
?>