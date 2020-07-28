<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CatchUndefinedVariable extends Analyzer {
    /* 1 methods */

    public function testExceptions_CatchUndefinedVariable01()  { $this->generic_test('Exceptions/CatchUndefinedVariable.01'); }
}
?>