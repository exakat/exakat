<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NamedRegex extends Analyzer {
    /* 1 methods */

    public function testStructures_NamedRegex01()  { $this->generic_test('Structures/NamedRegex.01'); }
}
?>