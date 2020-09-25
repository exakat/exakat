<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnsupportedTypesWithOperators extends Analyzer {
    /* 3 methods */

    public function testStructures_UnsupportedTypesWithOperators01()  { $this->generic_test('Structures/UnsupportedTypesWithOperators.01'); }
    public function testStructures_UnsupportedTypesWithOperators02()  { $this->generic_test('Structures/UnsupportedTypesWithOperators.02'); }
    public function testStructures_UnsupportedTypesWithOperators03()  { $this->generic_test('Structures/UnsupportedTypesWithOperators.03'); }
}
?>