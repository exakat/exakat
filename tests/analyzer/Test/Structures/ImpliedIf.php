<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ImpliedIf extends Analyzer {
    /* 3 methods */

    public function testStructures_ImpliedIf01()  { $this->generic_test('Structures_ImpliedIf.01'); }
    public function testStructures_ImpliedIf02()  { $this->generic_test('Structures_ImpliedIf.02'); }
    public function testStructures_ImpliedIf03()  { $this->generic_test('Structures/ImpliedIf.03'); }
}
?>