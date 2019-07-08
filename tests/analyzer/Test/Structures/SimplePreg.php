<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SimplePreg extends Analyzer {
    /* 4 methods */

    public function testStructures_SimplePreg01()  { $this->generic_test('Structures/SimplePreg.01'); }
    public function testStructures_SimplePreg02()  { $this->generic_test('Structures/SimplePreg.02'); }
    public function testStructures_SimplePreg03()  { $this->generic_test('Structures/SimplePreg.03'); }
    public function testStructures_SimplePreg04()  { $this->generic_test('Structures/SimplePreg.04'); }
}
?>