<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldTypeWithBool extends Analyzer {
    /* 1 methods */

    public function testFunctions_CouldTypeWithBool01()  { $this->generic_test('Functions/CouldTypeWithBool.01'); }
}
?>