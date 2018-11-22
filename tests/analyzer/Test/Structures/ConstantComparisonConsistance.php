<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConstantComparisonConsistance extends Analyzer {
    /* 4 methods */

    public function testStructures_ConstantComparisonConsistance01()  { $this->generic_test('Structures_ConstantComparisonConsistance.01'); }
    public function testStructures_ConstantComparisonConsistance02()  { $this->generic_test('Structures_ConstantComparisonConsistance.02'); }
    public function testStructures_ConstantComparisonConsistance03()  { $this->generic_test('Structures_ConstantComparisonConsistance.03'); }
    public function testStructures_ConstantComparisonConsistance04()  { $this->generic_test('Structures/ConstantComparisonConsistance.04'); }
}
?>