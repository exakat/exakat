<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConstantScalarExpression extends Analyzer {
    /* 4 methods */

    public function testStructures_ConstantScalarExpression01()  { $this->generic_test('Structures_ConstantScalarExpression.01'); }
    public function testStructures_ConstantScalarExpression02()  { $this->generic_test('Structures_ConstantScalarExpression.02'); }
    public function testStructures_ConstantScalarExpression03()  { $this->generic_test('Structures_ConstantScalarExpression.03'); }
    public function testStructures_ConstantScalarExpression04()  { $this->generic_test('Structures/ConstantScalarExpression.04'); }
}
?>