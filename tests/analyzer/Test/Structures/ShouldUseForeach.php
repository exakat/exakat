<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ShouldUseForeach extends Analyzer {
    /* 1 methods */

    public function testStructures_ShouldUseForeach01()  { $this->generic_test('Structures/ShouldUseForeach.01'); }
}
?>