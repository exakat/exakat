<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ComparedButNotAssignedStrings extends Analyzer {
    /* 1 methods */

    public function testStructures_ComparedButNotAssignedStrings01()  { $this->generic_test('Structures/ComparedButNotAssignedStrings.01'); }
}
?>