<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SameConditions extends Analyzer {
    /* 4 methods */

    public function testStructures_SameConditions01()  { $this->generic_test('Structures/SameConditions.01'); }
    public function testStructures_SameConditions02()  { $this->generic_test('Structures/SameConditions.02'); }
    public function testStructures_SameConditions03()  { $this->generic_test('Structures/SameConditions.03'); }
    public function testStructures_SameConditions04()  { $this->generic_test('Structures/SameConditions.04'); }
}
?>