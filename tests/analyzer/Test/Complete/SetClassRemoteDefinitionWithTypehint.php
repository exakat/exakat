<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassRemoteDefinitionWithTypehint extends Analyzer {
    /* 2 methods */

    public function testComplete_SetClassRemoteDefinitionWithTypehint01()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithTypehint.01'); }
    public function testComplete_SetClassRemoteDefinitionWithTypehint02()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithTypehint.02'); }
}
?>