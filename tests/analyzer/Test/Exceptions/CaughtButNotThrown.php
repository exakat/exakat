<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CaughtButNotThrown extends Analyzer {
    /* 2 methods */

    public function testExceptions_CaughtButNotThrown01()  { $this->generic_test('Exceptions/CaughtButNotThrown.01'); }
    public function testExceptions_CaughtButNotThrown02()  { $this->generic_test('Exceptions/CaughtButNotThrown.02'); }
}
?>