<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongCase extends Analyzer {
    /* 1 methods */

    public function testFunctions_WrongCase01()  { $this->generic_test('Functions/WrongCase.01'); }
}
?>