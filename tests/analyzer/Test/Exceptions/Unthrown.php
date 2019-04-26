<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Unthrown extends Analyzer {
    /* 1 methods */

    public function testExceptions_Unthrown01()  { $this->generic_test('Exceptions_Unthrown.01'); }
}
?>