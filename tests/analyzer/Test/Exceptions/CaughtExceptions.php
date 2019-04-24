<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CaughtExceptions extends Analyzer {
    /* 2 methods */

    public function testExceptions_CaughtExceptions01()  { $this->generic_test('Exceptions_CaughtExceptions.01'); }
    public function testExceptions_CaughtExceptions02()  { $this->generic_test('Exceptions/CaughtExceptions.02'); }
}
?>