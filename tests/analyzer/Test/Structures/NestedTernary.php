<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NestedTernary extends Analyzer {
    /* 3 methods */

    public function testStructures_NestedTernary01()  { $this->generic_test('Structures_NestedTernary.01'); }
    public function testStructures_NestedTernary02()  { $this->generic_test('Structures/NestedTernary.02'); }
    public function testStructures_NestedTernary03()  { $this->generic_test('Structures/NestedTernary.03'); }
}
?>