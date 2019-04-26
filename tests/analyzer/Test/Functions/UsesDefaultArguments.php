<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UsesDefaultArguments extends Analyzer {
    /* 1 methods */

    public function testFunctions_UsesDefaultArguments01()  { $this->generic_test('Functions_UsesDefaultArguments.01'); }
}
?>