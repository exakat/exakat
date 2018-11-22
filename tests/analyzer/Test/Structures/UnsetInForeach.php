<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnsetInForeach extends Analyzer {
    /* 5 methods */

    public function testStructures_UnsetInForeach01()  { $this->generic_test('Structures_UnsetInForeach.01'); }
    public function testStructures_UnsetInForeach02()  { $this->generic_test('Structures_UnsetInForeach.02'); }
    public function testStructures_UnsetInForeach03()  { $this->generic_test('Structures_UnsetInForeach.03'); }
    public function testStructures_UnsetInForeach04()  { $this->generic_test('Structures_UnsetInForeach.04'); }
    public function testStructures_UnsetInForeach05()  { $this->generic_test('Structures_UnsetInForeach.05'); }
}
?>