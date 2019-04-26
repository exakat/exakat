<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AutoUnsetForeach extends Analyzer {
    /* 1 methods */

    public function testStructures_AutoUnsetForeach01()  { $this->generic_test('Structures/AutoUnsetForeach.01'); }
}
?>