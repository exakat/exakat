<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class IsModified extends Analyzer {
    /* 4 methods */

    public function testArrays_IsModified01()  { $this->generic_test('Arrays/IsModified.01'); }
    public function testArrays_IsModified02()  { $this->generic_test('Arrays/IsModified.02'); }
    public function testArrays_IsModified03()  { $this->generic_test('Arrays/IsModified.03'); }
    public function testArrays_IsModified04()  { $this->generic_test('Arrays/IsModified.04'); }
}
?>