<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetParentDefinition extends Analyzer {
    /* 1 methods */

    public function testComplete_SetParentDefinition01()  { $this->generic_test('Complete/SetParentDefinition.01'); }
}
?>