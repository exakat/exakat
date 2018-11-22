<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DanglingArrayReferences extends Analyzer {
    /* 2 methods */

    public function testStructures_DanglingArrayReferences01()  { $this->generic_test('Structures_DanglingArrayReferences.01'); }
    public function testStructures_DanglingArrayReferences02()  { $this->generic_test('Structures_DanglingArrayReferences.02'); }
}
?>