<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessCheck extends Analyzer {
    /* 3 methods */

    public function testStructures_UselessCheck01()  { $this->generic_test('Structures/UselessCheck.01'); }
    public function testStructures_UselessCheck02()  { $this->generic_test('Structures/UselessCheck.02'); }
    public function testStructures_UselessCheck03()  { $this->generic_test('Structures/UselessCheck.03'); }
}
?>