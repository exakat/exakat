<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ResultMayBeMissing extends Analyzer {
    /* 1 methods */

    public function testStructures_ResultMayBeMissing01()  { $this->generic_test('Structures/ResultMayBeMissing.01'); }
}
?>