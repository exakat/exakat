<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extcairo extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extcairo01()  { $this->generic_test('Extensions_Extcairo.01'); }
}
?>