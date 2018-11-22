<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoChangeIncomingVariables extends Analyzer {
    /* 2 methods */

    public function testStructures_NoChangeIncomingVariables01()  { $this->generic_test('Structures_NoChangeIncomingVariables.01'); }
    public function testStructures_NoChangeIncomingVariables02()  { $this->generic_test('Structures_NoChangeIncomingVariables.02'); }
}
?>