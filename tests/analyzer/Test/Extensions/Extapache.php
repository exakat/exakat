<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extapache extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extapache01()  { $this->generic_test('Extensions_Extapache.01'); }
}
?>