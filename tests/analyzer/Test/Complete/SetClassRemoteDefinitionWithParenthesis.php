<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassRemoteDefinitionWithParenthesis extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassRemoteDefinitionWithParenthesis01()  { $this->generic_test('Complete/SetClassRemoteDefinitionWithParenthesis.01'); }
}
?>