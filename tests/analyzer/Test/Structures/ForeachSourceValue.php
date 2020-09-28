<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ForeachSourceValue extends Analyzer {
    /* 3 methods */

    public function testStructures_ForeachSourceValue01()  { $this->generic_test('Structures/ForeachSourceValue.01'); }
    public function testStructures_ForeachSourceValue02()  { $this->generic_test('Structures/ForeachSourceValue.02'); }
    public function testStructures_ForeachSourceValue03()  { $this->generic_test('Structures/ForeachSourceValue.03'); }
}
?>