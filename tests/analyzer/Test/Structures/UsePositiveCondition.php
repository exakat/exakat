<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UsePositiveCondition extends Analyzer {
    /* 3 methods */

    public function testStructures_UsePositiveCondition01()  { $this->generic_test('Structures/UsePositiveCondition.01'); }
    public function testStructures_UsePositiveCondition02()  { $this->generic_test('Structures/UsePositiveCondition.02'); }
    public function testStructures_UsePositiveCondition03()  { $this->generic_test('Structures/UsePositiveCondition.03'); }
}
?>