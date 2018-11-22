<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AlreadyCaught extends Analyzer {
    /* 4 methods */

    public function testExceptions_AlreadyCaught01()  { $this->generic_test('Exceptions/AlreadyCaught.01'); }
    public function testExceptions_AlreadyCaught02()  { $this->generic_test('Exceptions/AlreadyCaught.02'); }
    public function testExceptions_AlreadyCaught03()  { $this->generic_test('Exceptions/AlreadyCaught.03'); }
    public function testExceptions_AlreadyCaught04()  { $this->generic_test('Exceptions/AlreadyCaught.04'); }
}
?>