<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UsedFunctions extends Analyzer {
    /* 9 methods */

    public function testFunctions_UsedFunctions01()  { $this->generic_test('Functions_UsedFunctions.01'); }
    public function testFunctions_UsedFunctions02()  { $this->generic_test('Functions_UsedFunctions.02'); }
    public function testFunctions_UsedFunctions03()  { $this->generic_test('Functions_UsedFunctions.03'); }
    public function testFunctions_UsedFunctions04()  { $this->generic_test('Functions_UsedFunctions.04'); }
    public function testFunctions_UsedFunctions05()  { $this->generic_test('Functions_UsedFunctions.05'); }
    public function testFunctions_UsedFunctions06()  { $this->generic_test('Functions_UsedFunctions.06'); }
    public function testFunctions_UsedFunctions07()  { $this->generic_test('Functions_UsedFunctions.07'); }
    public function testFunctions_UsedFunctions08()  { $this->generic_test('Functions_UsedFunctions.08'); }
    public function testFunctions_UsedFunctions09()  { $this->generic_test('Functions/UsedFunctions.09'); }
}
?>