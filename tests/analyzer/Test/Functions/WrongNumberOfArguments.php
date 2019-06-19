<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongNumberOfArguments extends Analyzer {
    /* 12 methods */

    public function testFunctions_WrongNumberOfArguments01()  { $this->generic_test('Functions_WrongNumberOfArguments.01'); }
    public function testFunctions_WrongNumberOfArguments02()  { $this->generic_test('Functions_WrongNumberOfArguments.02'); }
    public function testFunctions_WrongNumberOfArguments03()  { $this->generic_test('Functions_WrongNumberOfArguments.03'); }
    public function testFunctions_WrongNumberOfArguments04()  { $this->generic_test('Functions/WrongNumberOfArguments.04'); }
    public function testFunctions_WrongNumberOfArguments05()  { $this->generic_test('Functions/WrongNumberOfArguments.05'); }
    public function testFunctions_WrongNumberOfArguments06()  { $this->generic_test('Functions/WrongNumberOfArguments.06'); }
    public function testFunctions_WrongNumberOfArguments07()  { $this->generic_test('Functions/WrongNumberOfArguments.07'); }
    public function testFunctions_WrongNumberOfArguments08()  { $this->generic_test('Functions/WrongNumberOfArguments.08'); }
    public function testFunctions_WrongNumberOfArguments09()  { $this->generic_test('Functions/WrongNumberOfArguments.09'); }
    public function testFunctions_WrongNumberOfArguments10()  { $this->generic_test('Functions/WrongNumberOfArguments.10'); }
    public function testFunctions_WrongNumberOfArguments11()  { $this->generic_test('Functions/WrongNumberOfArguments.11'); }
    public function testFunctions_WrongNumberOfArguments12()  { $this->generic_test('Functions/WrongNumberOfArguments.12'); }
}
?>