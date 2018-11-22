<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extopenssl extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extopenssl01()  { $this->generic_test('Extensions_Extopenssl.01'); }
}
?>