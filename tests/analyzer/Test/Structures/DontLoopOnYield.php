<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DontLoopOnYield extends Analyzer {
    /* 1 methods */

    public function testStructures_DontLoopOnYield01()  { $this->generic_test('Structures/DontLoopOnYield.01'); }
}
?>