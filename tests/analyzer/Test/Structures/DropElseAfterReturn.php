<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DropElseAfterReturn extends Analyzer {
    /* 5 methods */

    public function testStructures_DropElseAfterReturn01()  { $this->generic_test('Structures/DropElseAfterReturn.01'); }
    public function testStructures_DropElseAfterReturn02()  { $this->generic_test('Structures/DropElseAfterReturn.02'); }
    public function testStructures_DropElseAfterReturn03()  { $this->generic_test('Structures/DropElseAfterReturn.03'); }
    public function testStructures_DropElseAfterReturn04()  { $this->generic_test('Structures/DropElseAfterReturn.04'); }
    public function testStructures_DropElseAfterReturn05()  { $this->generic_test('Structures/DropElseAfterReturn.05'); }
}
?>