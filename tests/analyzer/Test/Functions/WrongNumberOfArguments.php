<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WrongNumberOfArguments extends Analyzer {
    /* 5 methods */

    public function testFunctions_WrongNumberOfArguments01()  { $this->generic_test('Functions_WrongNumberOfArguments.01'); }
    public function testFunctions_WrongNumberOfArguments02()  { $this->generic_test('Functions_WrongNumberOfArguments.02'); }
    public function testFunctions_WrongNumberOfArguments03()  { $this->generic_test('Functions_WrongNumberOfArguments.03'); }
    public function testFunctions_WrongNumberOfArguments04()  { $this->generic_test('Functions/WrongNumberOfArguments.04'); }
    public function testFunctions_WrongNumberOfArguments05()  { $this->generic_test('Functions/WrongNumberOfArguments.05'); }
}
?>