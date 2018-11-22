<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class InvalidPackFormat extends Analyzer {
    /* 2 methods */

    public function testStructures_InvalidPackFormat01()  { $this->generic_test('Structures/InvalidPackFormat.01'); }
    public function testStructures_InvalidPackFormat02()  { $this->generic_test('Structures/InvalidPackFormat.02'); }
}
?>