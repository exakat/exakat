<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldUseForeach extends Analyzer {
    /* 3 methods */

    public function testStructures_ShouldUseForeach01()  { $this->generic_test('Structures/ShouldUseForeach.01'); }
    public function testStructures_ShouldUseForeach02()  { $this->generic_test('Structures/ShouldUseForeach.02'); }
    public function testStructures_ShouldUseForeach03()  { $this->generic_test('Structures/ShouldUseForeach.03'); }
}
?>