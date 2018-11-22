<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AutoUnsetForeach extends Analyzer {
    /* 1 methods */

    public function testStructures_AutoUnsetForeach01()  { $this->generic_test('Structures/AutoUnsetForeach.01'); }
}
?>