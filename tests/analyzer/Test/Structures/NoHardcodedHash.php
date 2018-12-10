<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoHardcodedHash extends Analyzer {
    /* 5 methods */

    public function testStructures_NoHardcodedHash01()  { $this->generic_test('Structures/NoHardcodedHash.01'); }
    public function testStructures_NoHardcodedHash02()  { $this->generic_test('Structures/NoHardcodedHash.02'); }
    public function testStructures_NoHardcodedHash03()  { $this->generic_test('Structures/NoHardcodedHash.03'); }
    public function testStructures_NoHardcodedHash04()  { $this->generic_test('Structures/NoHardcodedHash.04'); }
    public function testStructures_NoHardcodedHash05()  { $this->generic_test('Structures/NoHardcodedHash.05'); }
}
?>