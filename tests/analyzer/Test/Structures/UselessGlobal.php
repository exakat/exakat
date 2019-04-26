<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessGlobal extends Analyzer {
    /* 4 methods */

    public function testStructures_UselessGlobal01()  { $this->generic_test('Structures_UselessGlobal.01'); }
    public function testStructures_UselessGlobal02()  { $this->generic_test('Structures_UselessGlobal.02'); }
    public function testStructures_UselessGlobal03()  { $this->generic_test('Structures/UselessGlobal.03'); }
    public function testStructures_UselessGlobal04()  { $this->generic_test('Structures/UselessGlobal.04'); }
}
?>