<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class BreakNonInteger extends Analyzer {
    /* 1 methods */

    public function testStructures_BreakNonInteger01()  { $this->generic_test('Structures_BreakNonInteger.01'); }
}
?>