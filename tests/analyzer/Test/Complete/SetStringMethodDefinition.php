<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetStringMethodDefinition extends Analyzer {
    /* 1 methods */

    public function testComplete_SetStringMethodDefinition01()  { $this->generic_test('Complete/SetStringMethodDefinition.01'); }
}
?>