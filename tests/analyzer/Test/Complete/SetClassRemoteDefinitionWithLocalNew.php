<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassRemoteDefinitionWithLocalNew extends Analyzer {
    /* 2 methods */

    public function testComplete_SetClassRemoteDefinitionWithLocalNew01()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithLocalNew.01'); }
    public function testComplete_SetClassRemoteDefinitionWithLocalNew02()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithLocalNew.02'); }
}
?>