<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ForeachReferenceIsNotModified extends Analyzer {
    /* 5 methods */

    public function testStructures_ForeachReferenceIsNotModified01()  { $this->generic_test('Structures_ForeachReferenceIsNotModified.01'); }
    public function testStructures_ForeachReferenceIsNotModified02()  { $this->generic_test('Structures_ForeachReferenceIsNotModified.02'); }
    public function testStructures_ForeachReferenceIsNotModified03()  { $this->generic_test('Structures/ForeachReferenceIsNotModified.03'); }
    public function testStructures_ForeachReferenceIsNotModified04()  { $this->generic_test('Structures/ForeachReferenceIsNotModified.04'); }
    public function testStructures_ForeachReferenceIsNotModified05()  { $this->generic_test('Structures/ForeachReferenceIsNotModified.05'); }
}
?>