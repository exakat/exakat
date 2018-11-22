<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Dynamiccall extends Analyzer {
    /* 1 methods */

    public function testFunctions_Dynamiccall01()  { $this->generic_test('Functions_Dynamiccall.01'); }
}
?>