<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TypehintedReferences extends Analyzer {
    /* 3 methods */

    public function testFunctions_TypehintedReferences01()  { $this->generic_test('Functions/TypehintedReferences.01'); }
    public function testFunctions_TypehintedReferences02()  { $this->generic_test('Functions/TypehintedReferences.02'); }
    public function testFunctions_TypehintedReferences03()  { $this->generic_test('Functions/TypehintedReferences.03'); }
}
?>