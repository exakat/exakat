<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NotOrNot extends Analyzer {
    /* 2 methods */

    public function testStructures_NotOrNot01()  { $this->generic_test('Structures/NotOrNot.01'); }
    public function testStructures_NotOrNot02()  { $this->generic_test('Structures/NotOrNot.02'); }
}
?>