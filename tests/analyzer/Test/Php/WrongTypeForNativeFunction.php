<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongTypeForNativeFunction extends Analyzer {
    /* 5 methods */

    public function testPhp_WrongTypeForNativeFunction01()  { $this->generic_test('Php/WrongTypeForNativeFunction.01'); }
    public function testPhp_WrongTypeForNativeFunction02()  { $this->generic_test('Php/WrongTypeForNativeFunction.02'); }
    public function testPhp_WrongTypeForNativeFunction03()  { $this->generic_test('Php/WrongTypeForNativeFunction.03'); }
    public function testPhp_WrongTypeForNativeFunction04()  { $this->generic_test('Php/WrongTypeForNativeFunction.04'); }
    public function testPhp_WrongTypeForNativeFunction05()  { $this->generic_test('Php/WrongTypeForNativeFunction.05'); }
}
?>