<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongTypeWithCall extends Analyzer {
    /* 2 methods */

    public function testFunctions_WrongTypeWithCall01()  { $this->generic_test('Functions/WrongTypeWithCall.01'); }
    public function testFunctions_WrongTypeWithCall02()  { $this->generic_test('Functions/WrongTypeWithCall.02'); }
}
?>