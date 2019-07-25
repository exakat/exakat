<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FailingSubstrComparison extends Analyzer {
    /* 8 methods */

    public function testStructures_FailingSubstrComparison01()  { $this->generic_test('Structures/FailingSubstrComparison.01'); }
    public function testStructures_FailingSubstrComparison02()  { $this->generic_test('Structures/FailingSubstrComparison.02'); }
    public function testStructures_FailingSubstrComparison03()  { $this->generic_test('Structures/FailingSubstrComparison.03'); }
    public function testStructures_FailingSubstrComparison04()  { $this->generic_test('Structures/FailingSubstrComparison.04'); }
    public function testStructures_FailingSubstrComparison05()  { $this->generic_test('Structures/FailingSubstrComparison.05'); }
    public function testStructures_FailingSubstrComparison06()  { $this->generic_test('Structures/FailingSubstrComparison.06'); }
    public function testStructures_FailingSubstrComparison07()  { $this->generic_test('Structures/FailingSubstrComparison.07'); }
    public function testStructures_FailingSubstrComparison08()  { $this->generic_test('Structures/FailingSubstrComparison.08'); }
}
?>