<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class StaticLoop extends Analyzer {
    /* 12 methods */

    public function testStructures_StaticLoop01()  { $this->generic_test('Structures_StaticLoop.01'); }
    public function testStructures_StaticLoop02()  { $this->generic_test('Structures_StaticLoop.02'); }
    public function testStructures_StaticLoop03()  { $this->generic_test('Structures_StaticLoop.03'); }
    public function testStructures_StaticLoop04()  { $this->generic_test('Structures_StaticLoop.04'); }
    public function testStructures_StaticLoop05()  { $this->generic_test('Structures/StaticLoop.05'); }
    public function testStructures_StaticLoop06()  { $this->generic_test('Structures/StaticLoop.06'); }
    public function testStructures_StaticLoop07()  { $this->generic_test('Structures/StaticLoop.07'); }
    public function testStructures_StaticLoop08()  { $this->generic_test('Structures/StaticLoop.08'); }
    public function testStructures_StaticLoop09()  { $this->generic_test('Structures/StaticLoop.09'); }
    public function testStructures_StaticLoop10()  { $this->generic_test('Structures/StaticLoop.10'); }
    public function testStructures_StaticLoop11()  { $this->generic_test('Structures/StaticLoop.11'); }
    public function testStructures_StaticLoop12()  { $this->generic_test('Structures/StaticLoop.12'); }
}
?>