<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extnsapi extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extnsapi01()  { $this->generic_test('Extensions/Extnsapi.01'); }
}
?>