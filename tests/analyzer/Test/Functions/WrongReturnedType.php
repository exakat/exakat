<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongReturnedType extends Analyzer {
    /* 9 methods */

    public function testFunctions_WrongReturnedType01()  { $this->generic_test('Functions/WrongReturnedType.01'); }
    public function testFunctions_WrongReturnedType02()  { $this->generic_test('Functions/WrongReturnedType.02'); }
    public function testFunctions_WrongReturnedType03()  { $this->generic_test('Functions/WrongReturnedType.03'); }
    public function testFunctions_WrongReturnedType04()  { $this->generic_test('Functions/WrongReturnedType.04'); }
    public function testFunctions_WrongReturnedType05()  { $this->generic_test('Functions/WrongReturnedType.05'); }
    public function testFunctions_WrongReturnedType06()  { $this->generic_test('Functions/WrongReturnedType.06'); }
    public function testFunctions_WrongReturnedType07()  { $this->generic_test('Functions/WrongReturnedType.07'); }
    public function testFunctions_WrongReturnedType08()  { $this->generic_test('Functions/WrongReturnedType.08'); }
    public function testFunctions_WrongReturnedType09()  { $this->generic_test('Functions/WrongReturnedType.09'); }
}
?>