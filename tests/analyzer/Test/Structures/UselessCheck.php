<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UselessCheck extends Analyzer {
    /* 2 methods */

    public function testStructures_UselessCheck01()  { $this->generic_test('Structures/UselessCheck.01'); }
    public function testStructures_UselessCheck02()  { $this->generic_test('Structures/UselessCheck.02'); }
}
?>