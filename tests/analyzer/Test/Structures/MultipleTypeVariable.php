<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleTypeVariable extends Analyzer {
    /* 3 methods */

    public function testStructures_MultipleTypeVariable01()  { $this->generic_test('Structures/MultipleTypeVariable.01'); }
    public function testStructures_MultipleTypeVariable02()  { $this->generic_test('Structures/MultipleTypeVariable.02'); }
    public function testStructures_MultipleTypeVariable03()  { $this->generic_test('Structures/MultipleTypeVariable.03'); }
}
?>