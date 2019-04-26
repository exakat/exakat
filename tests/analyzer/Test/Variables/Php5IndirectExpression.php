<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php5IndirectExpression extends Analyzer {
    /* 1 methods */

    public function testVariables_Php5IndirectExpression01()  { $this->generic_test('Variables_Php5IndirectExpression.01'); }
}
?>