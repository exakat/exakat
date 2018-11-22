<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extfile extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extfile01()  { $this->generic_test('Extensions_Extfile.01'); }
}
?>