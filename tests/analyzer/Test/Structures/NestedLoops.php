<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NestedLoops extends Analyzer {
    /* 4 methods */

    public function testStructures_NestedLoops01()  { $this->generic_test('Structures_NestedLoops.01'); }
    public function testStructures_NestedLoops02()  { $this->generic_test('Structures_NestedLoops.02'); }
    public function testStructures_NestedLoops03()  { $this->generic_test('Structures_NestedLoops.03'); }
    public function testStructures_NestedLoops04()  { $this->generic_test('Structures_NestedLoops.04'); }
}
?>