<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoHardcodedIp extends Analyzer {
    /* 3 methods */

    public function testStructures_NoHardcodedIp01()  { $this->generic_test('Structures_NoHardcodedIp.01'); }
    public function testStructures_NoHardcodedIp02()  { $this->generic_test('Structures/NoHardcodedIp.02'); }
    public function testStructures_NoHardcodedIp03()  { $this->generic_test('Structures/NoHardcodedIp.03'); }
}
?>