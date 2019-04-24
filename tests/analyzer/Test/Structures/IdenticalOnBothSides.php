<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IdenticalOnBothSides extends Analyzer {
    /* 2 methods */

    public function testStructures_IdenticalOnBothSides01()  { $this->generic_test('Structures/IdenticalOnBothSides.01'); }
    public function testStructures_IdenticalOnBothSides02()  { $this->generic_test('Structures/IdenticalOnBothSides.02'); }
}
?>