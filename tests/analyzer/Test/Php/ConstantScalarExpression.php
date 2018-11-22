<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConstantScalarExpression extends Analyzer {
    /* 3 methods */

    public function testPhp_ConstantScalarExpression01()  { $this->generic_test('Php/ConstantScalarExpression.01'); }
    public function testPhp_ConstantScalarExpression02()  { $this->generic_test('Php/ConstantScalarExpression.02'); }
    public function testPhp_ConstantScalarExpression03()  { $this->generic_test('Php/ConstantScalarExpression.03'); }
}
?>