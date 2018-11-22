<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class funcGetArgModified extends Analyzer {
    /* 1 methods */

    public function testFunctions_funcGetArgModified01()  { $this->generic_test('Functions/funcGetArgModified.01'); }
}
?>