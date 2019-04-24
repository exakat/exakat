<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoIssetWithEmpty extends Analyzer {
    /* 1 methods */

    public function testStructures_NoIssetWithEmpty01()  { $this->generic_test('Structures/NoIssetWithEmpty.01'); }
}
?>