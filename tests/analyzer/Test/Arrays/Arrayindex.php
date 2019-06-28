<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Arrayindex extends Analyzer {
    /* 3 methods */

    public function testArrays_Arrayindex01()  { $this->generic_test('Arrays/Arrayindex.01'); }
    public function testArrays_Arrayindex02()  { $this->generic_test('Arrays/Arrayindex.02'); }
    public function testArrays_Arrayindex03()  { $this->generic_test('Arrays/Arrayindex.03'); }
}
?>