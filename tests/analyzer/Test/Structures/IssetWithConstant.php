<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IssetWithConstant extends Analyzer {
    /* 3 methods */

    public function testStructures_IssetWithConstant01()  { $this->generic_test('Structures_IssetWithConstant.01'); }
    public function testStructures_IssetWithConstant02()  { $this->generic_test('Structures_IssetWithConstant.02'); }
    public function testStructures_IssetWithConstant03()  { $this->generic_test('Structures/IssetWithConstant.03'); }
}
?>