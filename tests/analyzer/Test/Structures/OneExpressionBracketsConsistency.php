<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class OneExpressionBracketsConsistency extends Analyzer {
    /* 5 methods */

    public function testStructures_OneExpressionBracketsConsistency01()  { $this->generic_test('Structures/OneExpressionBracketsConsistency.01'); }
    public function testStructures_OneExpressionBracketsConsistency02()  { $this->generic_test('Structures/OneExpressionBracketsConsistency.02'); }
    public function testStructures_OneExpressionBracketsConsistency03()  { $this->generic_test('Structures/OneExpressionBracketsConsistency.03'); }
    public function testStructures_OneExpressionBracketsConsistency04()  { $this->generic_test('Structures/OneExpressionBracketsConsistency.04'); }
    public function testStructures_OneExpressionBracketsConsistency05()  { $this->generic_test('Structures/OneExpressionBracketsConsistency.05'); }
}
?>