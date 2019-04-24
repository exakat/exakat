<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DynamicCode extends Analyzer {
    /* 4 methods */

    public function testStructures_DynamicCode01()  { $this->generic_test('Structures_DynamicCode.01'); }
    public function testStructures_DynamicCode02()  { $this->generic_test('Structures/DynamicCode.02'); }
    public function testStructures_DynamicCode03()  { $this->generic_test('Structures/DynamicCode.03'); }
    public function testStructures_DynamicCode04()  { $this->generic_test('Structures/DynamicCode.04'); }
}
?>