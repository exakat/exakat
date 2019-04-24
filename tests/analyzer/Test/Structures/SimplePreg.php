<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SimplePreg extends Analyzer {
    /* 2 methods */

    public function testStructures_SimplePreg01()  { $this->generic_test('Structures/SimplePreg.01'); }
    public function testStructures_SimplePreg02()  { $this->generic_test('Structures/SimplePreg.02'); }
}
?>