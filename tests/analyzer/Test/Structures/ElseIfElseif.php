<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ElseIfElseif extends Analyzer {
    /* 2 methods */

    public function testStructures_ElseIfElseif01()  { $this->generic_test('Structures_ElseIfElseif.01'); }
    public function testStructures_ElseIfElseif02()  { $this->generic_test('Structures/ElseIfElseif.02'); }
}
?>