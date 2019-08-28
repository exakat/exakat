<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ArrayMergeAndVariadic extends Analyzer {
    /* 1 methods */

    public function testStructures_ArrayMergeAndVariadic01()  { $this->generic_test('Structures/ArrayMergeAndVariadic.01'); }
}
?>