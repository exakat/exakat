<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class BreakOutsideLoop extends Analyzer {
    /* 4 methods */

    public function testStructures_BreakOutsideLoop01()  { $this->generic_test('Structures_BreakOutsideLoop.01'); }
    public function testStructures_BreakOutsideLoop02()  { $this->generic_test('Structures_BreakOutsideLoop.02'); }
    public function testStructures_BreakOutsideLoop03()  { $this->generic_test('Structures_BreakOutsideLoop.03'); }
    public function testStructures_BreakOutsideLoop04()  { $this->generic_test('Structures_BreakOutsideLoop.04'); }
}
?>