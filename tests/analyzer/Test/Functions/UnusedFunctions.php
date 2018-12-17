<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnusedFunctions extends Analyzer {
    /* 9 methods */

    public function testFunctions_UnusedFunctions01()  { $this->generic_test('Functions_UnusedFunctions.01'); }
    public function testFunctions_UnusedFunctions02()  { $this->generic_test('Functions/UnusedFunctions.02'); }
    public function testFunctions_UnusedFunctions03()  { $this->generic_test('Functions/UnusedFunctions.03'); }
    public function testFunctions_UnusedFunctions04()  { $this->generic_test('Functions/UnusedFunctions.04'); }
    public function testFunctions_UnusedFunctions05()  { $this->generic_test('Functions/UnusedFunctions.05'); }
    public function testFunctions_UnusedFunctions06()  { $this->generic_test('Functions/UnusedFunctions.06'); }
    public function testFunctions_UnusedFunctions07()  { $this->generic_test('Functions/UnusedFunctions.07'); }
    public function testFunctions_UnusedFunctions08()  { $this->generic_test('Functions/UnusedFunctions.08'); }
    public function testFunctions_UnusedFunctions09()  { $this->generic_test('Functions/UnusedFunctions.09'); }
}
?>