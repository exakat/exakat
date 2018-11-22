<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extds extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extds01()  { $this->generic_test('Extensions/Extds.01'); }
}
?>