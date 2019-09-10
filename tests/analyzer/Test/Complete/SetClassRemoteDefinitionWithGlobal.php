<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassRemoteDefinitionWithGlobal extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassRemoteDefinitionWithGlobal01()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithGlobal.01'); }
}
?>