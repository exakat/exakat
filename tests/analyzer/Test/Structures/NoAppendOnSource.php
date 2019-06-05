<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoAppendOnSource extends Analyzer {
    /* 1 methods */

    public function testStructures_NoAppendOnSource01()  { $this->generic_test('Structures/NoAppendOnSource.01'); }
}
?>