<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PrintAndDie extends Analyzer {
    /* 2 methods */

    public function testStructures_PrintAndDie01()  { $this->generic_test('Structures_PrintAndDie.01'); }
    public function testStructures_PrintAndDie02()  { $this->generic_test('Structures/PrintAndDie.02'); }
}
?>