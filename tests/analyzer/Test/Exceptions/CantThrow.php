<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CantThrow extends Analyzer {
    /* 2 methods */

    public function testExceptions_CantThrow01()  { $this->generic_test('Exceptions/CantThrow.01'); }
    public function testExceptions_CantThrow02()  { $this->generic_test('Exceptions/CantThrow.02'); }
}
?>