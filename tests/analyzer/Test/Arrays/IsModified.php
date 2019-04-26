<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsModified extends Analyzer {
    /* 6 methods */

    public function testArrays_IsModified01()  { $this->generic_test('Arrays/IsModified.01'); }
    public function testArrays_IsModified02()  { $this->generic_test('Arrays/IsModified.02'); }
    public function testArrays_IsModified03()  { $this->generic_test('Arrays/IsModified.03'); }
    public function testArrays_IsModified04()  { $this->generic_test('Arrays/IsModified.04'); }
    public function testArrays_IsModified05()  { $this->generic_test('Arrays/IsModified.05'); }
    public function testArrays_IsModified06()  { $this->generic_test('Arrays/IsModified.06'); }
}
?>