<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ForgottenThrown extends Analyzer {
    /* 2 methods */

    public function testExceptions_ForgottenThrown01()  { $this->generic_test('Exceptions/ForgottenThrown.01'); }
    public function testExceptions_ForgottenThrown02()  { $this->generic_test('Exceptions/ForgottenThrown.02'); }
}
?>