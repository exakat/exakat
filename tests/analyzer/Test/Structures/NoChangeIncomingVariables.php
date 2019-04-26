<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoChangeIncomingVariables extends Analyzer {
    /* 2 methods */

    public function testStructures_NoChangeIncomingVariables01()  { $this->generic_test('Structures_NoChangeIncomingVariables.01'); }
    public function testStructures_NoChangeIncomingVariables02()  { $this->generic_test('Structures_NoChangeIncomingVariables.02'); }
}
?>