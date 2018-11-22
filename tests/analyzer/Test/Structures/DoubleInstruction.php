<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DoubleInstruction extends Analyzer {
    /* 2 methods */

    public function testStructures_DoubleInstruction01()  { $this->generic_test('Structures_DoubleInstruction.01'); }
    public function testStructures_DoubleInstruction02()  { $this->generic_test('Structures/DoubleInstruction.02'); }
}
?>