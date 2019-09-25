<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NeverUsedParameter extends Analyzer {
    /* 4 methods */

    public function testFunctions_NeverUsedParameter01()  { $this->generic_test('Functions/NeverUsedParameter.01'); }
    public function testFunctions_NeverUsedParameter02()  { $this->generic_test('Functions/NeverUsedParameter.02'); }
    public function testFunctions_NeverUsedParameter03()  { $this->generic_test('Functions/NeverUsedParameter.03'); }
    public function testFunctions_NeverUsedParameter04()  { $this->generic_test('Functions/NeverUsedParameter.04'); }
}
?>