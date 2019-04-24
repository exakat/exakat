<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AmbiguousKeys extends Analyzer {
    /* 4 methods */

    public function testArrays_AmbiguousKeys01()  { $this->generic_test('Arrays/AmbiguousKeys.01'); }
    public function testArrays_AmbiguousKeys02()  { $this->generic_test('Arrays/AmbiguousKeys.02'); }
    public function testArrays_AmbiguousKeys03()  { $this->generic_test('Arrays/AmbiguousKeys.03'); }
    public function testArrays_AmbiguousKeys04()  { $this->generic_test('Arrays/AmbiguousKeys.04'); }
}
?>