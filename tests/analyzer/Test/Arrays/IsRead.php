<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IsRead extends Analyzer {
    /* 3 methods */

    public function testArrays_IsRead01()  { $this->generic_test('Arrays/IsRead.01'); }
    public function testArrays_IsRead02()  { $this->generic_test('Arrays/IsRead.02'); }
    public function testArrays_IsRead03()  { $this->generic_test('Arrays/IsRead.03'); }
}
?>