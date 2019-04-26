<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FunctionsUsingReference extends Analyzer {
    /* 1 methods */

    public function testFunctions_FunctionsUsingReference01()  { $this->generic_test('Functions/FunctionsUsingReference.01'); }
}
?>