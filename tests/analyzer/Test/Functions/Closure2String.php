<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Closure2String extends Analyzer {
    /* 5 methods */

    public function testFunctions_Closure2String01()  { $this->generic_test('Functions/Closure2String.01'); }
    public function testFunctions_Closure2String02()  { $this->generic_test('Functions/Closure2String.02'); }
    public function testFunctions_Closure2String03()  { $this->generic_test('Functions/Closure2String.03'); }
    public function testFunctions_Closure2String04()  { $this->generic_test('Functions/Closure2String.04'); }
    public function testFunctions_Closure2String05()  { $this->generic_test('Functions/Closure2String.05'); }
}
?>