<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TryFinally extends Analyzer {
    /* 2 methods */

    public function testStructures_TryFinally01()  { $this->generic_test('Structures_TryFinally.01'); }
    public function testStructures_TryFinally02()  { $this->generic_test('Structures_TryFinally.02'); }
}
?>