<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WrongNumberOfArgumentsMethods extends Analyzer {
    /* 10 methods */

    public function testFunctions_WrongNumberOfArgumentsMethods01()  { $this->generic_test('Functions_WrongNumberOfArgumentsMethods.01'); }
    public function testFunctions_WrongNumberOfArgumentsMethods02()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.02'); }
    public function testFunctions_WrongNumberOfArgumentsMethods03()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.03'); }
    public function testFunctions_WrongNumberOfArgumentsMethods04()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.04'); }
    public function testFunctions_WrongNumberOfArgumentsMethods05()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.05'); }
    public function testFunctions_WrongNumberOfArgumentsMethods06()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.06'); }
    public function testFunctions_WrongNumberOfArgumentsMethods07()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.07'); }
    public function testFunctions_WrongNumberOfArgumentsMethods08()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.08'); }
    public function testFunctions_WrongNumberOfArgumentsMethods09()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.09'); }
    public function testFunctions_WrongNumberOfArgumentsMethods10()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.10'); }
}
?>