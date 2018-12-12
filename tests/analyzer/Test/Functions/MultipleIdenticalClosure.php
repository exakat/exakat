<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MultipleIdenticalClosure extends Analyzer {
    /* 2 methods */

    public function testFunctions_MultipleIdenticalClosure01()  { $this->generic_test('Functions/MultipleIdenticalClosure.01'); }
    public function testFunctions_MultipleIdenticalClosure02()  { $this->generic_test('Functions/MultipleIdenticalClosure.02'); }
}
?>