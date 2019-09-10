<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassRemoteDefinitionWithInjection extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassRemoteDefinitionWithInjection01()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithInjection.01'); }
}
?>