<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class OnceUsage extends Analyzer {
    /* 2 methods */

    public function testStructures_OnceUsage01()  { $this->generic_test('Structures_OnceUsage.01'); }
    public function testStructures_OnceUsage02()  { $this->generic_test('Structures/OnceUsage.02'); }
}
?>