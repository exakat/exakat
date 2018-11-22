<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class OptionalParameter extends Analyzer {
    /* 1 methods */

    public function testFunctions_OptionalParameter01()  { $this->generic_test('Functions/OptionalParameter.01'); }
}
?>