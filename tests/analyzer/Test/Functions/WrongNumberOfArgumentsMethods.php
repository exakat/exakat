<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WrongNumberOfArgumentsMethods extends Analyzer {
    /* 3 methods */

    public function testFunctions_WrongNumberOfArgumentsMethods01()  { $this->generic_test('Functions_WrongNumberOfArgumentsMethods.01'); }
    public function testFunctions_WrongNumberOfArgumentsMethods02()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.02'); }
    public function testFunctions_WrongNumberOfArgumentsMethods03()  { $this->generic_test('Functions/WrongNumberOfArgumentsMethods.03'); }
}
?>