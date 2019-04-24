<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NeverUsedParameter extends Analyzer {
    /* 2 methods */

    public function testFunctions_NeverUsedParameter01()  { $this->generic_test('Functions/NeverUsedParameter.01'); }
    public function testFunctions_NeverUsedParameter02()  { $this->generic_test('Functions/NeverUsedParameter.02'); }
}
?>