<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class FunctionSubscripting extends Analyzer {
    /* 2 methods */

    public function testStructures_FunctionSubscripting01()  { $this->generic_test('Structures_FunctionSubscripting.01'); }
    public function testStructures_FunctionSubscripting02()  { $this->generic_test('Structures/FunctionSubscripting.02'); }
}
?>