<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extzmq extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extzmq01()  { $this->generic_test('Extensions_Extzmq.01'); }
}
?>