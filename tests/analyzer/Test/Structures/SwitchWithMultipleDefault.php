<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SwitchWithMultipleDefault extends Analyzer {
    /* 1 methods */

    public function testStructures_SwitchWithMultipleDefault01()  { $this->generic_test('Structures_SwitchWithMultipleDefault.01'); }
}
?>