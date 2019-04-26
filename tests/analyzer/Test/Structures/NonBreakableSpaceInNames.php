<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NonBreakableSpaceInNames extends Analyzer {
    /* 1 methods */

    public function testStructures_NonBreakableSpaceInNames01()  { $this->generic_test('Structures/NonBreakableSpaceInNames.01'); }
}
?>