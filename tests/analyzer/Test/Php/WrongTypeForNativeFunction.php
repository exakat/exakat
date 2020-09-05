<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongTypeForNativeFunction extends Analyzer {
    /* 2 methods */

    public function testPhp_WrongTypeForNativeFunction01()  { $this->generic_test('Php/WrongTypeForNativeFunction.01'); }
    public function testPhp_WrongTypeForNativeFunction02()  { $this->generic_test('Php/WrongTypeForNativeFunction.02'); }
}
?>