<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongRange extends Analyzer {
    /* 2 methods */

    public function testStructures_WrongRange01()  { $this->generic_test('Structures/WrongRange.01'); }
    public function testStructures_WrongRange02()  { $this->generic_test('Structures/WrongRange.02'); }
}
?>