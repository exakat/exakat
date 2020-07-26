<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UndefinedFunctions extends Analyzer {
    /* 10 methods */

    public function testFunctions_UndefinedFunctions01()  { $this->generic_test('Functions_UndefinedFunctions.01'); }
    public function testFunctions_UndefinedFunctions02()  { $this->generic_test('Functions/UndefinedFunctions.02'); }
    public function testFunctions_UndefinedFunctions03()  { $this->generic_test('Functions/UndefinedFunctions.03'); }
    public function testFunctions_UndefinedFunctions04()  { $this->generic_test('Functions/UndefinedFunctions.04'); }
    public function testFunctions_UndefinedFunctions05()  { $this->generic_test('Functions/UndefinedFunctions.05'); }
    public function testFunctions_UndefinedFunctions06()  { $this->generic_test('Functions/UndefinedFunctions.06'); }
    public function testFunctions_UndefinedFunctions07()  { $this->generic_test('Functions/UndefinedFunctions.07'); }
    public function testFunctions_UndefinedFunctions08()  { $this->generic_test('Functions/UndefinedFunctions.08'); }
    public function testFunctions_UndefinedFunctions09()  { $this->generic_test('Functions/UndefinedFunctions.09'); }
    public function testFunctions_UndefinedFunctions10()  { $this->generic_test('Functions/UndefinedFunctions.10'); }
}
?>