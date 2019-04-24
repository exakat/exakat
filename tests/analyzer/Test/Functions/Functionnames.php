<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Functionnames extends Analyzer {
    /* 1 methods */

    public function testFunctions_Functionnames01()  { $this->generic_test('Functions_Functionnames.01'); }
}
?>