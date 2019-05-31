<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConcatEmpty extends Analyzer {
    /* 2 methods */

    public function testStructures_ConcatEmpty01()  { $this->generic_test('Structures/ConcatEmpty.01'); }
    public function testStructures_ConcatEmpty02()  { $this->generic_test('Structures/ConcatEmpty.02'); }
}
?>