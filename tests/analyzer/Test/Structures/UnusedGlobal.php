<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnusedGlobal extends Analyzer {
    /* 3 methods */

    public function testStructures_UnusedGlobal01()  { $this->generic_test('Structures_UnusedGlobal.01'); }
    public function testStructures_UnusedGlobal02()  { $this->generic_test('Structures_UnusedGlobal.02'); }
    public function testStructures_UnusedGlobal03()  { $this->generic_test('Structures_UnusedGlobal.03'); }
}
?>