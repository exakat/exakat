<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extgrpc extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extgrpc01()  { $this->generic_test('Extensions/Extgrpc.01'); }
}
?>