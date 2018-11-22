<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IsPhpException extends Analyzer {
    /* 1 methods */

    public function testExceptions_IsPhpException01()  { $this->generic_test('Exceptions/IsPhpException.01'); }
}
?>