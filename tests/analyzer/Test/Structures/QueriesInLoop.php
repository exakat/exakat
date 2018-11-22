<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class QueriesInLoop extends Analyzer {
    /* 3 methods */

    public function testStructures_QueriesInLoop01()  { $this->generic_test('Structures_QueriesInLoop.01'); }
    public function testStructures_QueriesInLoop02()  { $this->generic_test('Structures/QueriesInLoop.02'); }
    public function testStructures_QueriesInLoop03()  { $this->generic_test('Structures/QueriesInLoop.03'); }
}
?>