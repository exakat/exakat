<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class EvalUsage extends Analyzer {
    /* 2 methods */

    public function testStructures_EvalUsage01()  { $this->generic_test('Structures_EvalUsage.01'); }
    public function testStructures_EvalUsage02()  { $this->generic_test('Structures/EvalUsage.02'); }
}
?>