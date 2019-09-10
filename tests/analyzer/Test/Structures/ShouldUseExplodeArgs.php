<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseExplodeArgs extends Analyzer {
    /* 1 methods */

    public function testStructures_ShouldUseExplodeArgs01()  { $this->generic_test('Structures/ShouldUseExplodeArgs.01'); }
}
?>