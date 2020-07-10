<?php

namespace Test\Typehints;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeCallable extends Analyzer {
    /* 3 methods */

    public function testTypehints_CouldBeCallable01()  { $this->generic_test('Typehints/CouldBeCallable.01'); }
    public function testTypehints_CouldBeCallable02()  { $this->generic_test('Typehints/CouldBeCallable.02'); }
    public function testTypehints_CouldBeCallable03()  { $this->generic_test('Typehints/CouldBeCallable.03'); }
}
?>