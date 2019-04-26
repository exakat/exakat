<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleCatch extends Analyzer {
    /* 1 methods */

    public function testExceptions_MultipleCatch01()  { $this->generic_test('Exceptions/MultipleCatch.01'); }
}
?>