<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php7IndirectExpression extends Analyzer {
    /* 2 methods */

    public function testVariables_Php7IndirectExpression01()  { $this->generic_test('Variables_Php7IndirectExpression.01'); }
    public function testVariables_Php7IndirectExpression02()  { $this->generic_test('Variables/Php7IndirectExpression.02'); }
}
?>