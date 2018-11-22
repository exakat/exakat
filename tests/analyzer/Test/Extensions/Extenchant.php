<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extenchant extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extenchant01()  { $this->generic_test('Extensions_Extenchant.01'); }
}
?>