<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extphalcon extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extphalcon01()  { $this->generic_test('Extensions_Extphalcon.01'); }
}
?>