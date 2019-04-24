<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class VariableConstant extends Analyzer {
    /* 1 methods */

    public function testConstants_VariableConstant01()  { $this->generic_test('Constants_VariableConstant.01'); }
}
?>