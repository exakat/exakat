<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Closures extends Analyzer {
    /* 2 methods */

    public function testFunctions_Closures01()  { $this->generic_test('Functions_Closures.01'); }
    public function testFunctions_Closures02()  { $this->generic_test('Functions_Closures.02'); }
}
?>