<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseNullableType extends Analyzer {
    /* 5 methods */

    public function testPhp_UseNullableType01()  { $this->generic_test('Php/UseNullableType.01'); }
    public function testPhp_UseNullableType02()  { $this->generic_test('Php/UseNullableType.02'); }
    public function testPhp_UseNullableType03()  { $this->generic_test('Php/UseNullableType.03'); }
    public function testPhp_UseNullableType04()  { $this->generic_test('Php/UseNullableType.04'); }
    public function testPhp_UseNullableType05()  { $this->generic_test('Php/UseNullableType.05'); }
}
?>