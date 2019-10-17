<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldTypeWithString extends Analyzer {
    /* 1 methods */

    public function testFunctions_CouldTypeWithString01()  { $this->generic_test('Functions/CouldTypeWithString.01'); }
}
?>