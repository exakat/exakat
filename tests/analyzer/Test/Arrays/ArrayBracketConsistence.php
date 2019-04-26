<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ArrayBracketConsistence extends Analyzer {
    /* 2 methods */

    public function testArrays_ArrayBracketConsistence01()  { $this->generic_test('Arrays/ArrayBracketConsistence.01'); }
    public function testArrays_ArrayBracketConsistence02()  { $this->generic_test('Arrays/ArrayBracketConsistence.02'); }
}
?>