<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassAliasDefinition extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassAliasDefinition01()  { $this->generic_test('Complete/SetClassAliasDefinition.01'); }
}
?>