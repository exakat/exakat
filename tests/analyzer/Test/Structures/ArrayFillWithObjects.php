<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ArrayFillWithObjects extends Analyzer {
    /* 1 methods */

    public function testStructures_ArrayFillWithObjects01()  { $this->generic_test('Structures/ArrayFillWithObjects.01'); }
}
?>