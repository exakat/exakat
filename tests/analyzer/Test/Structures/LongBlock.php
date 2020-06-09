<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class LongBlock extends Analyzer {
    /* 1 methods */

    public function testStructures_LongBlock01()  { $this->generic_test('Structures/LongBlock.01'); }
}
?>