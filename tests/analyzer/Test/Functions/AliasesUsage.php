<?php

namespace Test\Functions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AliasesUsage extends Analyzer {
    /* 1 methods */

    public function testFunctions_AliasesUsage01()  { $this->generic_test('Functions_AliasesUsage.01'); }
}
?>