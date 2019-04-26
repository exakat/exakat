<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class VariableMayBeNonGlobal extends Analyzer {
    /* 1 methods */

    public function testStructures_VariableMayBeNonGlobal01()  { $this->generic_test('Structures/VariableMayBeNonGlobal.01'); }
}
?>