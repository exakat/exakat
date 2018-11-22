<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class OneLevelOfIndentation extends Analyzer {
    /* 2 methods */

    public function testStructures_OneLevelOfIndentation01()  { $this->generic_test('Structures/OneLevelOfIndentation.01'); }
    public function testStructures_OneLevelOfIndentation02()  { $this->generic_test('Structures/OneLevelOfIndentation.02'); }
}
?>