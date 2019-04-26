<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetExceptionHandlerPHP7 extends Analyzer {
    /* 2 methods */

    public function testPhp_SetExceptionHandlerPHP701()  { $this->generic_test('Php/SetExceptionHandlerPHP7.01'); }
    public function testPhp_SetExceptionHandlerPHP702()  { $this->generic_test('Php/SetExceptionHandlerPHP7.02'); }
}
?>