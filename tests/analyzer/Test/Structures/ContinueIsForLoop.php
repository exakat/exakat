<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ContinueIsForLoop extends Analyzer {
    /* 4 methods */

    public function testStructures_ContinueIsForLoop01()  { $this->generic_test('Structures/ContinueIsForLoop.01'); }
    public function testStructures_ContinueIsForLoop02()  { $this->generic_test('Structures/ContinueIsForLoop.02'); }
    public function testStructures_ContinueIsForLoop03()  { $this->generic_test('Structures/ContinueIsForLoop.03'); }
    public function testStructures_ContinueIsForLoop04()  { $this->generic_test('Structures/ContinueIsForLoop.04'); }
}
?>