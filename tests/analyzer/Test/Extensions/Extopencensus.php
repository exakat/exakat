<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extopencensus extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extopencensus01()  { $this->generic_test('Extensions/Extopencensus.01'); }
}
?>