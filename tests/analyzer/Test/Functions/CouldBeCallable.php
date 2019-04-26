<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeCallable extends Analyzer {
    /* 4 methods */

    public function testFunctions_CouldBeCallable01()  { $this->generic_test('Functions/CouldBeCallable.01'); }
    public function testFunctions_CouldBeCallable02()  { $this->generic_test('Functions/CouldBeCallable.02'); }
    public function testFunctions_CouldBeCallable03()  { $this->generic_test('Functions/CouldBeCallable.03'); }
    public function testFunctions_CouldBeCallable04()  { $this->generic_test('Functions/CouldBeCallable.04'); }
}
?>