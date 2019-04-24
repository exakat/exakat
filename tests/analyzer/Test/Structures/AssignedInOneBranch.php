<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AssignedInOneBranch extends Analyzer {
    /* 1 methods */

    public function testStructures_AssignedInOneBranch01()  { $this->generic_test('Structures/AssignedInOneBranch.01'); }
}
?>