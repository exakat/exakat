<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CoalesceAndConcat extends Analyzer {
    /* 1 methods */

    public function testStructures_CoalesceAndConcat01()  { $this->generic_test('Structures/CoalesceAndConcat.01'); }
}
?>