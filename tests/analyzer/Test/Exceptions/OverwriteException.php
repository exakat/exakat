<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OverwriteException extends Analyzer {
    /* 2 methods */

    public function testExceptions_OverwriteException01()  { $this->generic_test('Exceptions_OverwriteException.01'); }
    public function testExceptions_OverwriteException02()  { $this->generic_test('Exceptions/OverwriteException.02'); }
}
?>