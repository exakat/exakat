<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldPreprocess extends Analyzer {
    /* 9 methods */

    public function testStructures_ShouldPreprocess01()  { $this->generic_test('Structures_ShouldPreprocess.01'); }
    public function testStructures_ShouldPreprocess02()  { $this->generic_test('Structures_ShouldPreprocess.02'); }
    public function testStructures_ShouldPreprocess03()  { $this->generic_test('Structures/ShouldPreprocess.03'); }
    public function testStructures_ShouldPreprocess04()  { $this->generic_test('Structures/ShouldPreprocess.04'); }
    public function testStructures_ShouldPreprocess05()  { $this->generic_test('Structures/ShouldPreprocess.05'); }
    public function testStructures_ShouldPreprocess06()  { $this->generic_test('Structures/ShouldPreprocess.06'); }
    public function testStructures_ShouldPreprocess07()  { $this->generic_test('Structures/ShouldPreprocess.07'); }
    public function testStructures_ShouldPreprocess08()  { $this->generic_test('Structures/ShouldPreprocess.08'); }
    public function testStructures_ShouldPreprocess09()  { $this->generic_test('Structures/ShouldPreprocess.09'); }
}
?>