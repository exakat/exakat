<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MismatchTypeAndDefault extends Analyzer {
    /* 9 methods */

    public function testFunctions_MismatchTypeAndDefault01()  { $this->generic_test('Functions/MismatchTypeAndDefault.01'); }
    public function testFunctions_MismatchTypeAndDefault02()  { $this->generic_test('Functions/MismatchTypeAndDefault.02'); }
    public function testFunctions_MismatchTypeAndDefault03()  { $this->generic_test('Functions/MismatchTypeAndDefault.03'); }
    public function testFunctions_MismatchTypeAndDefault04()  { $this->generic_test('Functions/MismatchTypeAndDefault.04'); }
    public function testFunctions_MismatchTypeAndDefault05()  { $this->generic_test('Functions/MismatchTypeAndDefault.05'); }
    public function testFunctions_MismatchTypeAndDefault06()  { $this->generic_test('Functions/MismatchTypeAndDefault.06'); }
    public function testFunctions_MismatchTypeAndDefault07()  { $this->generic_test('Functions/MismatchTypeAndDefault.07'); }
    public function testFunctions_MismatchTypeAndDefault08()  { $this->generic_test('Functions/MismatchTypeAndDefault.08'); }
    public function testFunctions_MismatchTypeAndDefault09()  { $this->generic_test('Functions/MismatchTypeAndDefault.09'); }
}
?>