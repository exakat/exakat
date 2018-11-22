<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class OneLineTwoInstructions extends Analyzer {
    /* 7 methods */

    public function testStructures_OneLineTwoInstructions01()  { $this->generic_test('Structures_OneLineTwoInstructions.01'); }
    public function testStructures_OneLineTwoInstructions02()  { $this->generic_test('Structures_OneLineTwoInstructions.02'); }
    public function testStructures_OneLineTwoInstructions03()  { $this->generic_test('Structures_OneLineTwoInstructions.03'); }
    public function testStructures_OneLineTwoInstructions04()  { $this->generic_test('Structures_OneLineTwoInstructions.04'); }
    public function testStructures_OneLineTwoInstructions05()  { $this->generic_test('Structures/OneLineTwoInstructions.05'); }
    public function testStructures_OneLineTwoInstructions06()  { $this->generic_test('Structures/OneLineTwoInstructions.06'); }
    public function testStructures_OneLineTwoInstructions07()  { $this->generic_test('Structures/OneLineTwoInstructions.07'); }
}
?>