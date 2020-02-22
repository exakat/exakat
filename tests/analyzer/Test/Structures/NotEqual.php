<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NotEqual extends Analyzer {
    /* 1 methods */

    public function testStructures_NotEqual01()  { $this->generic_test('Structures/NotEqual.01'); }
}
?>