<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Bracketless extends Analyzer {
    /* 5 methods */

    public function testStructures_Bracketless01()  { $this->generic_test('Structures_Bracketless.01'); }
    public function testStructures_Bracketless02()  { $this->generic_test('Structures_Bracketless.02'); }
    public function testStructures_Bracketless03()  { $this->generic_test('Structures_Bracketless.03'); }
    public function testStructures_Bracketless04()  { $this->generic_test('Structures_Bracketless.04'); }
    public function testStructures_Bracketless05()  { $this->generic_test('Structures/Bracketless.05'); }
}
?>