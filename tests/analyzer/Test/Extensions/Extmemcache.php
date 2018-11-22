<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extmemcache extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extmemcache01()  { $this->generic_test('Extensions_Extmemcache.01'); }
}
?>