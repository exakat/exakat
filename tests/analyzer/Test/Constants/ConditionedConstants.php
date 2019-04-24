<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConditionedConstants extends Analyzer {
    /* 2 methods */

    public function testConstants_ConditionedConstants01()  { $this->generic_test('Constants_ConditionedConstants.01'); }
    public function testConstants_ConditionedConstants02()  { $this->generic_test('Constants_ConditionedConstants.02'); }
}
?>