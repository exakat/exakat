<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MultipleTypeVariable extends Analyzer {
    /* 2 methods */

    public function testStructures_MultipleTypeVariable01()  { $this->generic_test('Structures/MultipleTypeVariable.01'); }
    public function testStructures_MultipleTypeVariable02()  { $this->generic_test('Structures/MultipleTypeVariable.02'); }
}
?>