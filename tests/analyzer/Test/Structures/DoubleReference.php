<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DoubleReference extends Analyzer {
    /* 1 methods */

    public function testStructures_DoubleReference01()  { $this->generic_test('Structures/DoubleReference.01'); }
}
?>