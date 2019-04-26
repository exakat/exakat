<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Rethrown extends Analyzer {
    /* 2 methods */

    public function testExceptions_Rethrown01()  { $this->generic_test('Exceptions/Rethrown.01'); }
    public function testExceptions_Rethrown02()  { $this->generic_test('Exceptions/Rethrown.02'); }
}
?>