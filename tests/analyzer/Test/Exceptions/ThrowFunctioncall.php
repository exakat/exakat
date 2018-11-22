<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ThrowFunctioncall extends Analyzer {
    /* 2 methods */

    public function testExceptions_ThrowFunctioncall01()  { $this->generic_test('Exceptions/ThrowFunctioncall.01'); }
    public function testExceptions_ThrowFunctioncall02()  { $this->generic_test('Exceptions/ThrowFunctioncall.02'); }
}
?>