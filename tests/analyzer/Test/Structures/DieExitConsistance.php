<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DieExitConsistance extends Analyzer {
    /* 2 methods */

    public function testStructures_DieExitConsistance01()  { $this->generic_test('Structures/DieExitConsistance.01'); }
    public function testStructures_DieExitConsistance02()  { $this->generic_test('Structures/DieExitConsistance.02'); }
}
?>