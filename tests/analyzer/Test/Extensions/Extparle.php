<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extparle extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extparle01()  { $this->generic_test('Extensions/Extparle.01'); }
}
?>