<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DefinedExceptions extends Analyzer {
    /* 3 methods */

    public function testExceptions_DefinedExceptions01()  { $this->generic_test('Exceptions_DefinedExceptions.01'); }
    public function testExceptions_DefinedExceptions02()  { $this->generic_test('Exceptions_DefinedExceptions.02'); }
    public function testExceptions_DefinedExceptions03()  { $this->generic_test('Exceptions/DefinedExceptions.03'); }
}
?>