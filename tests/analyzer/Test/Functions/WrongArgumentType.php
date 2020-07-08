<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongArgumentType extends Analyzer {
    /* 5 methods */

    public function testFunctions_WrongArgumentType01()  { $this->generic_test('Functions/WrongArgumentType.01'); }
    public function testFunctions_WrongArgumentType02()  { $this->generic_test('Functions/WrongArgumentType.02'); }
    public function testFunctions_WrongArgumentType03()  { $this->generic_test('Functions/WrongArgumentType.03'); }
    public function testFunctions_WrongArgumentType04()  { $this->generic_test('Functions/WrongArgumentType.04'); }
    public function testFunctions_WrongArgumentType05()  { $this->generic_test('Functions/WrongArgumentType.05'); }
}
?>