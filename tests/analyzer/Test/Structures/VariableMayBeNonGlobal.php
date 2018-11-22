<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class VariableMayBeNonGlobal extends Analyzer {
    /* 1 methods */

    public function testStructures_VariableMayBeNonGlobal01()  { $this->generic_test('Structures/VariableMayBeNonGlobal.01'); }
}
?>