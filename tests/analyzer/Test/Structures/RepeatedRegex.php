<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RepeatedRegex extends Analyzer {
    /* 1 methods */

    public function testStructures_RepeatedRegex01()  { $this->generic_test('Structures/RepeatedRegex.01'); }
}
?>