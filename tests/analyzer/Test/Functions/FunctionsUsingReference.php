<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class FunctionsUsingReference extends Analyzer {
    /* 1 methods */

    public function testFunctions_FunctionsUsingReference01()  { $this->generic_test('Functions/FunctionsUsingReference.01'); }
}
?>