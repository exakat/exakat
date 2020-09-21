<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongTypeWithCall extends Analyzer {
    /* 5 methods */

    public function testFunctions_WrongTypeWithCall01()  { $this->generic_test('Functions/WrongTypeWithCall.01'); }
    public function testFunctions_WrongTypeWithCall02()  { $this->generic_test('Functions/WrongTypeWithCall.02'); }
    public function testFunctions_WrongTypeWithCall03()  { $this->generic_test('Functions/WrongTypeWithCall.03'); }
    public function testFunctions_WrongTypeWithCall04()  { $this->generic_test('Functions/WrongTypeWithCall.04'); }
    public function testFunctions_WrongTypeWithCall05()  { $this->generic_test('Functions/WrongTypeWithCall.05'); }
}
?>