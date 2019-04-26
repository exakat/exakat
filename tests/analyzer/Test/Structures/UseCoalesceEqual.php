<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseCoalesceEqual extends Analyzer {
    /* 1 methods */

    public function testStructures_UseCoalesceEqual01()  { $this->generic_test('Structures/UseCoalesceEqual.01'); }
}
?>