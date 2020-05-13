<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ThrowWasAnExpression extends Analyzer {
    /* 2 methods */

    public function testPhp_ThrowWasAnExpression01()  { $this->generic_test('Php/ThrowWasAnExpression.01'); }
    public function testPhp_ThrowWasAnExpression02()  { $this->generic_test('Php/ThrowWasAnExpression.02'); }
}
?>