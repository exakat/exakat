<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseCaseValue extends Analyzer {
    /* 3 methods */

    public function testStructures_UseCaseValue01()  { $this->generic_test('Structures/UseCaseValue.01'); }
    public function testStructures_UseCaseValue02()  { $this->generic_test('Structures/UseCaseValue.02'); }
    public function testStructures_UseCaseValue03()  { $this->generic_test('Structures/UseCaseValue.03'); }
}
?>