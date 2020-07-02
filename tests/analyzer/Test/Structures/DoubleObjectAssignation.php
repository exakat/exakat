<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DoubleObjectAssignation extends Analyzer {
    /* 1 methods */

    public function testStructures_DoubleObjectAssignation01()  { $this->generic_test('Structures/DoubleObjectAssignation.01'); }
}
?>