<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetArrayClassDefinition extends Analyzer {
    /* 1 methods */

    public function testComplete_SetArrayClassDefinition01()  { $this->generic_test('Complete/SetArrayClassDefinition.01'); }
}
?>