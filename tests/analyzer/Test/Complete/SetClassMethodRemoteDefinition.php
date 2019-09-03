<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassMethodRemoteDefinition extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassMethodRemoteDefinition01()  { $this->generic_test('Complete/SetClassMethodRemoteDefinition.01'); }
}
?>