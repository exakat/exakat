<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConditionedFunctions extends Analyzer {
    /* 2 methods */

    public function testFunctions_ConditionedFunctions01()  { $this->generic_test('Functions_ConditionedFunctions.01'); }
    public function testFunctions_ConditionedFunctions02()  { $this->generic_test('Functions_ConditionedFunctions.02'); }
}
?>