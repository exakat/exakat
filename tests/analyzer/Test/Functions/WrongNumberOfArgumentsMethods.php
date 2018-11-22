<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class WrongNumberOfArgumentsMethods extends Analyzer {
    /* 1 methods */

    public function testFunctions_WrongNumberOfArgumentsMethods01()  { $this->generic_test('Functions_WrongNumberOfArgumentsMethods.01'); }
}
?>