<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IfWithSameConditions extends Analyzer {
    /* 1 methods */

    public function testStructures_IfWithSameConditions01()  { $this->generic_test('Structures/IfWithSameConditions.01'); }
}
?>