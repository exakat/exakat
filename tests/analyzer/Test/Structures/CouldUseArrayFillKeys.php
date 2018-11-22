<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldUseArrayFillKeys extends Analyzer {
    /* 2 methods */

    public function testStructures_CouldUseArrayFillKeys01()  { $this->generic_test('Structures/CouldUseArrayFillKeys.01'); }
    public function testStructures_CouldUseArrayFillKeys02()  { $this->generic_test('Structures/CouldUseArrayFillKeys.02'); }
}
?>