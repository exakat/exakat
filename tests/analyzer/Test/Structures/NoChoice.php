<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoChoice extends Analyzer {
    /* 4 methods */

    public function testStructures_NoChoice01()  { $this->generic_test('Structures/NoChoice.01'); }
    public function testStructures_NoChoice02()  { $this->generic_test('Structures/NoChoice.02'); }
    public function testStructures_NoChoice03()  { $this->generic_test('Structures/NoChoice.03'); }
    public function testStructures_NoChoice04()  { $this->generic_test('Structures/NoChoice.04'); }
}
?>