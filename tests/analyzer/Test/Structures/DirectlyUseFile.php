<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DirectlyUseFile extends Analyzer {
    /* 1 methods */

    public function testStructures_DirectlyUseFile01()  { $this->generic_test('Structures/DirectlyUseFile.01'); }
}
?>