<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UselessReferenceArgument extends Analyzer {
    /* 1 methods */

    public function testFunctions_UselessReferenceArgument01()  { $this->generic_test('Functions/UselessReferenceArgument.01'); }
}
?>