<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsPhpException extends Analyzer {
    /* 2 methods */

    public function testExceptions_IsPhpException01()  { $this->generic_test('Exceptions/IsPhpException.01'); }
    public function testExceptions_IsPhpException02()  { $this->generic_test('Exceptions/IsPhpException.02'); }
}
?>