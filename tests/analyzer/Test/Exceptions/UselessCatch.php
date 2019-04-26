<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessCatch extends Analyzer {
    /* 1 methods */

    public function testExceptions_UselessCatch01()  { $this->generic_test('Exceptions/UselessCatch.01'); }
}
?>