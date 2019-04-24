<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseConstant extends Analyzer {
    /* 2 methods */

    public function testStructures_UseConstant01()  { $this->generic_test('Structures_UseConstant.01'); }
    public function testStructures_UseConstant02()  { $this->generic_test('Structures_UseConstant.02'); }
}
?>