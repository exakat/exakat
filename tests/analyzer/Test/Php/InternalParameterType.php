<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class InternalParameterType extends Analyzer {
    /* 2 methods */

    public function testPhp_InternalParameterType01()  { $this->generic_test('Php/InternalParameterType.01'); }
    public function testPhp_InternalParameterType02()  { $this->generic_test('Php/InternalParameterType.02'); }
}
?>