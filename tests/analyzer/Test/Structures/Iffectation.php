<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Iffectation extends Analyzer {
    /* 3 methods */

    public function testStructures_Iffectation01()  { $this->generic_test('Structures_Iffectation.01'); }
    public function testStructures_Iffectation02()  { $this->generic_test('Structures_Iffectation.02'); }
    public function testStructures_Iffectation03()  { $this->generic_test('Structures_Iffectation.03'); }
}
?>