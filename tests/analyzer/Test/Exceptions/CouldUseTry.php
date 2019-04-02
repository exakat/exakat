<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldUseTry extends Analyzer {
    /* 6 methods */

    public function testExceptions_CouldUseTry01()  { $this->generic_test('Exceptions/CouldUseTry.01'); }
    public function testExceptions_CouldUseTry02()  { $this->generic_test('Exceptions/CouldUseTry.02'); }
    public function testExceptions_CouldUseTry03()  { $this->generic_test('Exceptions/CouldUseTry.03'); }
    public function testExceptions_CouldUseTry04()  { $this->generic_test('Exceptions/CouldUseTry.04'); }
    public function testExceptions_CouldUseTry05()  { $this->generic_test('Exceptions/CouldUseTry.05'); }
    public function testExceptions_CouldUseTry06()  { $this->generic_test('Exceptions/CouldUseTry.06'); }
}
?>