<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class LargeTryBlock extends Analyzer {
    /* 1 methods */

    public function testExceptions_LargeTryBlock01()  { $this->generic_test('Exceptions/LargeTryBlock.01'); }
}
?>