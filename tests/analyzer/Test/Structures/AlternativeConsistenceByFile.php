<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AlternativeConsistenceByFile extends Analyzer {
    /* 4 methods */

    public function testStructures_AlternativeConsistenceByFile01()  { $this->generic_test('Structures/AlternativeConsistenceByFile.01'); }
    public function testStructures_AlternativeConsistenceByFile02()  { $this->generic_test('Structures/AlternativeConsistenceByFile.02'); }
    public function testStructures_AlternativeConsistenceByFile03()  { $this->generic_test('Structures/AlternativeConsistenceByFile.03'); }
    public function testStructures_AlternativeConsistenceByFile04()  { $this->generic_test('Structures/AlternativeConsistenceByFile.04'); }
}
?>