<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AvoidBooleanArgument extends Analyzer {
    /* 1 methods */

    public function testFunctions_AvoidBooleanArgument01()  { $this->generic_test('Functions/AvoidBooleanArgument.01'); }
}
?>