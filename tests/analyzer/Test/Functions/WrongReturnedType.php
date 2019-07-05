<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongReturnedType extends Analyzer {
    /* 5 methods */

    public function testFunctions_WrongReturnedType01()  { $this->generic_test('Functions/WrongReturnedType.01'); }
    public function testFunctions_WrongReturnedType02()  { $this->generic_test('Functions/WrongReturnedType.02'); }
    public function testFunctions_WrongReturnedType03()  { $this->generic_test('Functions/WrongReturnedType.03'); }
    public function testFunctions_WrongReturnedType04()  { $this->generic_test('Functions/WrongReturnedType.04'); }
    public function testFunctions_WrongReturnedType05()  { $this->generic_test('Functions/WrongReturnedType.05'); }
}
?>