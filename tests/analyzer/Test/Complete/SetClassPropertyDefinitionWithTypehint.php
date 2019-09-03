<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassPropertyDefinitionWithTypehint extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassPropertyDefinitionWithTypehint01()  { $this->generic_test('Complete/SetClassPropertyDefinitionWithTypehint.01'); }
}
?>