<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Phparrayindex extends Analyzer {
    /* 1 methods */

    public function testArrays_Phparrayindex01()  { $this->generic_test('Arrays/Phparrayindex.01'); }
}
?>