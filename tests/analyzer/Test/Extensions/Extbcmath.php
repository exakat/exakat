<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extbcmath extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extbcmath01()  { $this->generic_test('Extensions_Extbcmath.01'); }
    public function testExtensions_Extbcmath02()  { $this->generic_test('Extensions_Extbcmath.02'); }
}
?>