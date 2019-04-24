<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class EmptyFunction extends Analyzer {
    /* 11 methods */

    public function testFunctions_EmptyFunction01()  { $this->generic_test('Functions_EmptyFunction.01'); }
    public function testFunctions_EmptyFunction02()  { $this->generic_test('Functions_EmptyFunction.02'); }
    public function testFunctions_EmptyFunction03()  { $this->generic_test('Functions_EmptyFunction.03'); }
    public function testFunctions_EmptyFunction04()  { $this->generic_test('Functions/EmptyFunction.04'); }
    public function testFunctions_EmptyFunction05()  { $this->generic_test('Functions/EmptyFunction.05'); }
    public function testFunctions_EmptyFunction06()  { $this->generic_test('Functions/EmptyFunction.06'); }
    public function testFunctions_EmptyFunction07()  { $this->generic_test('Functions/EmptyFunction.07'); }
    public function testFunctions_EmptyFunction08()  { $this->generic_test('Functions/EmptyFunction.08'); }
    public function testFunctions_EmptyFunction09()  { $this->generic_test('Functions/EmptyFunction.09'); }
    public function testFunctions_EmptyFunction10()  { $this->generic_test('Functions/EmptyFunction.10'); }
    public function testFunctions_EmptyFunction11()  { $this->generic_test('Functions/EmptyFunction.11'); }
}
?>