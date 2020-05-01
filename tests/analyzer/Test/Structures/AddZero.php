<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AddZero extends Analyzer {
    /* 10 methods */

    public function testStructures_AddZero01()  { $this->generic_test('Structures_AddZero.01'); }
    public function testStructures_AddZero02()  { $this->generic_test('Structures_AddZero.02'); }
    public function testStructures_AddZero03()  { $this->generic_test('Structures/AddZero.03'); }
    public function testStructures_AddZero04()  { $this->generic_test('Structures/AddZero.04'); }
    public function testStructures_AddZero05()  { $this->generic_test('Structures/AddZero.05'); }
    public function testStructures_AddZero06()  { $this->generic_test('Structures/AddZero.06'); }
    public function testStructures_AddZero07()  { $this->generic_test('Structures/AddZero.07'); }
    public function testStructures_AddZero08()  { $this->generic_test('Structures/AddZero.08'); }
    public function testStructures_AddZero09()  { $this->generic_test('Structures/AddZero.09'); }
    public function testStructures_AddZero10()  { $this->generic_test('Structures/AddZero.10'); }
}
?>