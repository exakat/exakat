<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessReferenceArgument extends Analyzer {
    /* 4 methods */

    public function testFunctions_UselessReferenceArgument01()  { $this->generic_test('Functions/UselessReferenceArgument.01'); }
    public function testFunctions_UselessReferenceArgument02()  { $this->generic_test('Functions/UselessReferenceArgument.02'); }
    public function testFunctions_UselessReferenceArgument03()  { $this->generic_test('Functions/UselessReferenceArgument.03'); }
    public function testFunctions_UselessReferenceArgument04()  { $this->generic_test('Functions/UselessReferenceArgument.04'); }
}
?>