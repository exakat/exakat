<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessTypeCheck extends Analyzer {
    /* 2 methods */

    public function testFunctions_UselessTypeCheck01()  { $this->generic_test('Functions/UselessTypeCheck.01'); }
    public function testFunctions_UselessTypeCheck02()  { $this->generic_test('Functions/UselessTypeCheck.02'); }
}
?>