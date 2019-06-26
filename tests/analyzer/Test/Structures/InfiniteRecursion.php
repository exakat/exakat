<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class InfiniteRecursion extends Analyzer {
    /* 3 methods */

    public function testStructures_InfiniteRecursion01()  { $this->generic_test('Structures/InfiniteRecursion.01'); }
    public function testStructures_InfiniteRecursion02()  { $this->generic_test('Structures/InfiniteRecursion.02'); }
    public function testStructures_InfiniteRecursion03()  { $this->generic_test('Structures/InfiniteRecursion.03'); }
}
?>