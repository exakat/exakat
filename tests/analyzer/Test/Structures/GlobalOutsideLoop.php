<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class GlobalOutsideLoop extends Analyzer {
    /* 1 methods */

    public function testStructures_GlobalOutsideLoop01()  { $this->generic_test('Structures_GlobalOutsideLoop.01'); }
}
?>