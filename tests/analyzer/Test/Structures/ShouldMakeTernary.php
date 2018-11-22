<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ShouldMakeTernary extends Analyzer {
    /* 2 methods */

    public function testStructures_ShouldMakeTernary01()  { $this->generic_test('Structures/ShouldMakeTernary.01'); }
    public function testStructures_ShouldMakeTernary02()  { $this->generic_test('Structures/ShouldMakeTernary.02'); }
}
?>