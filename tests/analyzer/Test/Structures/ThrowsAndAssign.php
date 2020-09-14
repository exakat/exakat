<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ThrowsAndAssign extends Analyzer {
    /* 2 methods */

    public function testStructures_ThrowsAndAssign01()  { $this->generic_test('Structures_ThrowsAndAssign.01'); }
    public function testStructures_ThrowsAndAssign02()  { $this->generic_test('Structures/ThrowsAndAssign.02'); }
}
?>