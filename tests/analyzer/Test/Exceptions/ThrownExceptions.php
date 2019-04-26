<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ThrownExceptions extends Analyzer {
    /* 1 methods */

    public function testExceptions_ThrownExceptions01()  { $this->generic_test('Exceptions_ThrownExceptions.01'); }
}
?>