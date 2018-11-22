<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MultipleDeclarations extends Analyzer {
    /* 1 methods */

    public function testFunctions_MultipleDeclarations01()  { $this->generic_test('Functions/MultipleDeclarations.01'); }
}
?>