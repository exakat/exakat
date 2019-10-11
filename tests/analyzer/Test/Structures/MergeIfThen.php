<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MergeIfThen extends Analyzer {
    /* 1 methods */

    public function testStructures_MergeIfThen01()  { $this->generic_test('Structures/MergeIfThen.01'); }
}
?>