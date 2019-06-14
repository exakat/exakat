<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ComplexDynamicNames extends Analyzer {
    /* 1 methods */

    public function testVariables_ComplexDynamicNames01()  { $this->generic_test('Variables/ComplexDynamicNames.01'); }
}
?>