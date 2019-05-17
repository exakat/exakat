<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TernaryInConcat extends Analyzer {
    /* 6 methods */

    public function testStructures_TernaryInConcat01()  { $this->generic_test('Structures/TernaryInConcat.01'); }
    public function testStructures_TernaryInConcat02()  { $this->generic_test('Structures/TernaryInConcat.02'); }
    public function testStructures_TernaryInConcat03()  { $this->generic_test('Structures/TernaryInConcat.03'); }
    public function testStructures_TernaryInConcat04()  { $this->generic_test('Structures/TernaryInConcat.04'); }
}
?>