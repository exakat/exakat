<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class EmptyWithExpression extends Analyzer {
    /* 2 methods */

    public function testStructures_EmptyWithExpression01()  { $this->generic_test('Structures_EmptyWithExpression.01'); }
    public function testStructures_EmptyWithExpression02()  { $this->generic_test('Structures_EmptyWithExpression.02'); }
}
?>