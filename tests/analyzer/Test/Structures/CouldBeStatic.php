<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldBeStatic extends Analyzer {
    /* 4 methods */

    public function testStructures_CouldBeStatic01()  { $this->generic_test('Structures_CouldBeStatic.01'); }
    public function testStructures_CouldBeStatic02()  { $this->generic_test('Structures/CouldBeStatic.02'); }
    public function testStructures_CouldBeStatic03()  { $this->generic_test('Structures/CouldBeStatic.03'); }
    public function testStructures_CouldBeStatic04()  { $this->generic_test('Structures/CouldBeStatic.04'); }
}
?>