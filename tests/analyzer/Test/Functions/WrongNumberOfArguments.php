<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongNumberOfArguments extends Analyzer {
    /* 7 methods */

    public function testFunctions_WrongNumberOfArguments01()  { $this->generic_test('Functions_WrongNumberOfArguments.01'); }
    public function testFunctions_WrongNumberOfArguments02()  { $this->generic_test('Functions_WrongNumberOfArguments.02'); }
    public function testFunctions_WrongNumberOfArguments03()  { $this->generic_test('Functions_WrongNumberOfArguments.03'); }
    public function testFunctions_WrongNumberOfArguments04()  { $this->generic_test('Functions/WrongNumberOfArguments.04'); }
    public function testFunctions_WrongNumberOfArguments05()  { $this->generic_test('Functions/WrongNumberOfArguments.05'); }
    public function testFunctions_WrongNumberOfArguments06()  { $this->generic_test('Functions/WrongNumberOfArguments.06'); }
    public function testFunctions_WrongNumberOfArguments07()  { $this->generic_test('Functions/WrongNumberOfArguments.07'); }
}
?>