<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class GlobalInGlobal extends Analyzer {
    /* 2 methods */

    public function testStructures_GlobalInGlobal01()  { $this->generic_test('Structures_GlobalInGlobal.01'); }
    public function testStructures_GlobalInGlobal02()  { $this->generic_test('Structures/GlobalInGlobal.02'); }
}
?>