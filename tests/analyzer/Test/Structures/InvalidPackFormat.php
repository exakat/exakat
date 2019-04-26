<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class InvalidPackFormat extends Analyzer {
    /* 3 methods */

    public function testStructures_InvalidPackFormat01()  { $this->generic_test('Structures/InvalidPackFormat.01'); }
    public function testStructures_InvalidPackFormat02()  { $this->generic_test('Structures/InvalidPackFormat.02'); }
    public function testStructures_InvalidPackFormat03()  { $this->generic_test('Structures/InvalidPackFormat.03'); }
}
?>