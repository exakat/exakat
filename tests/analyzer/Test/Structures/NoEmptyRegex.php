<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoEmptyRegex extends Analyzer {
    /* 3 methods */

    public function testStructures_NoEmptyRegex01()  { $this->generic_test('Structures/NoEmptyRegex.01'); }
    public function testStructures_NoEmptyRegex02()  { $this->generic_test('Structures/NoEmptyRegex.02'); }
    public function testStructures_NoEmptyRegex03()  { $this->generic_test('Structures/NoEmptyRegex.03'); }
}
?>