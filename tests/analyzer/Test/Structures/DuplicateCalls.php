<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DuplicateCalls extends Analyzer {
    /* 3 methods */

    public function testStructures_DuplicateCalls01()  { $this->generic_test('Structures_DuplicateCalls.01'); }
    public function testStructures_DuplicateCalls02()  { $this->generic_test('Structures_DuplicateCalls.02'); }
    public function testStructures_DuplicateCalls03()  { $this->generic_test('Structures/DuplicateCalls.03'); }
}
?>