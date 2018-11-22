<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoHardcodedPort extends Analyzer {
    /* 1 methods */

    public function testStructures_NoHardcodedPort01()  { $this->generic_test('Structures_NoHardcodedPort.01'); }
}
?>