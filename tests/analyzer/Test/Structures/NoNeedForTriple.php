<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoNeedForTriple extends Analyzer {
    /* 1 methods */

    public function testStructures_NoNeedForTriple01()  { $this->generic_test('Structures/NoNeedForTriple.01'); }
}
?>