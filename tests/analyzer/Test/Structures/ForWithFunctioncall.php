<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ForWithFunctioncall extends Analyzer {
    /* 2 methods */

    public function testStructures_ForWithFunctioncall01()  { $this->generic_test('Structures_ForWithFunctioncall.01'); }
    public function testStructures_ForWithFunctioncall02()  { $this->generic_test('Structures/ForWithFunctioncall.02'); }
}
?>