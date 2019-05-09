<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class InvalidRegex extends Analyzer {
    /* 9 methods */

    public function testStructures_InvalidRegex01()  { $this->generic_test('Structures/InvalidRegex.01'); }
    public function testStructures_InvalidRegex02()  { $this->generic_test('Structures/InvalidRegex.02'); }
    public function testStructures_InvalidRegex03()  { $this->generic_test('Structures/InvalidRegex.03'); }
    public function testStructures_InvalidRegex04()  { $this->generic_test('Structures/InvalidRegex.04'); }
    public function testStructures_InvalidRegex05()  { $this->generic_test('Structures/InvalidRegex.05'); }
    public function testStructures_InvalidRegex06()  { $this->generic_test('Structures/InvalidRegex.06'); }
    public function testStructures_InvalidRegex07()  { $this->generic_test('Structures/InvalidRegex.07'); }
    public function testStructures_InvalidRegex08()  { $this->generic_test('Structures/InvalidRegex.08'); }
    public function testStructures_InvalidRegex09()  { $this->generic_test('Structures/InvalidRegex.09'); }
}
?>