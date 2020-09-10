<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnsupportedOperandTypes extends Analyzer {
    /* 3 methods */

    public function testStructures_UnsupportedOperandTypes01()  { $this->generic_test('Structures/UnsupportedOperandTypes.01'); }
    public function testStructures_UnsupportedOperandTypes02()  { $this->generic_test('Structures/UnsupportedOperandTypes.02'); }
    public function testStructures_UnsupportedOperandTypes03()  { $this->generic_test('Structures/UnsupportedOperandTypes.03'); }
}
?>