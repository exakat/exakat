<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldUseTry extends Analyzer {
    /* 4 methods */

    public function testExceptions_CouldUseTry01()  { $this->generic_test('Exceptions/CouldUseTry.01'); }
    public function testExceptions_CouldUseTry02()  { $this->generic_test('Exceptions/CouldUseTry.02'); }
    public function testExceptions_CouldUseTry03()  { $this->generic_test('Exceptions/CouldUseTry.03'); }
    public function testExceptions_CouldUseTry04()  { $this->generic_test('Exceptions/CouldUseTry.04'); }
}
?>