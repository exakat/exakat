<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnsupportedOperandTypes extends Analyzer {
    /* 1 methods */

    public function testStructures_UnsupportedOperandTypes01()  { $this->generic_test('Structures/UnsupportedOperandTypes.01'); }
}
?>