<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseForeach extends Analyzer {
    /* 1 methods */

    public function testStructures_ShouldUseForeach01()  { $this->generic_test('Structures/ShouldUseForeach.01'); }
}
?>