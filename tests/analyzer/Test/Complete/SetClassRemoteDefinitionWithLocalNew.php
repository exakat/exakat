<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassRemoteDefinitionWithLocalNew extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassRemoteDefinitionWithLocalNew01()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithLocalNew.01'); }
}
?>