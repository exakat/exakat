<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extpcntl extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extpcntl01()  { $this->generic_test('Extensions_Extpcntl.01'); }
}
?>