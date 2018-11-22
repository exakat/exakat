<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NestedTernary extends Analyzer {
    /* 2 methods */

    public function testStructures_NestedTernary01()  { $this->generic_test('Structures_NestedTernary.01'); }
    public function testStructures_NestedTernary02()  { $this->generic_test('Structures/NestedTernary.02'); }
}
?>