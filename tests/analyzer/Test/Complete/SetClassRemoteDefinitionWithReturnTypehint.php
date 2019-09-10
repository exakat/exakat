<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassRemoteDefinitionWithReturnTypehint extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassRemoteDefinitionWithReturnTypehint01()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithReturnTypehint.01'); }
}
?>