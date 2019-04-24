<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MixedKeys extends Analyzer {
    /* 3 methods */

    public function testArrays_MixedKeys01()  { $this->generic_test('Arrays/MixedKeys.01'); }
    public function testArrays_MixedKeys02()  { $this->generic_test('Arrays/MixedKeys.02'); }
    public function testArrays_MixedKeys03()  { $this->generic_test('Arrays/MixedKeys.03'); }
}
?>