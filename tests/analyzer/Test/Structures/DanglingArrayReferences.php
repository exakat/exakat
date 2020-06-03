<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DanglingArrayReferences extends Analyzer {
    /* 3 methods */

    public function testStructures_DanglingArrayReferences01()  { $this->generic_test('Structures_DanglingArrayReferences.01'); }
    public function testStructures_DanglingArrayReferences02()  { $this->generic_test('Structures_DanglingArrayReferences.02'); }
    public function testStructures_DanglingArrayReferences03()  { $this->generic_test('Structures/DanglingArrayReferences.03'); }
}
?>