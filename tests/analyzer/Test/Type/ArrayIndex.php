<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ArrayIndex extends Analyzer {
    /* 1 methods */

    public function testType_ArrayIndex01()  { $this->generic_test('Type/ArrayIndex.01'); }
}
?>