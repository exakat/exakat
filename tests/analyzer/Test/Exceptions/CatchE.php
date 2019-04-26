<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CatchE extends Analyzer {
    /* 1 methods */

    public function testExceptions_CatchE01()  { $this->generic_test('Exceptions/CatchE.01'); }
}
?>