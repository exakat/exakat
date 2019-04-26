<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleReturn extends Analyzer {
    /* 3 methods */

    public function testFunctions_MultipleReturn01()  { $this->generic_test('Functions_MultipleReturn.01'); }
    public function testFunctions_MultipleReturn02()  { $this->generic_test('Functions_MultipleReturn.02'); }
    public function testFunctions_MultipleReturn03()  { $this->generic_test('Functions/MultipleReturn.03'); }
}
?>