<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PossibleInfiniteLoop extends Analyzer {
    /* 2 methods */

    public function testStructures_PossibleInfiniteLoop01()  { $this->generic_test('Structures/PossibleInfiniteLoop.01'); }
    public function testStructures_PossibleInfiniteLoop02()  { $this->generic_test('Structures/PossibleInfiniteLoop.02'); }
}
?>