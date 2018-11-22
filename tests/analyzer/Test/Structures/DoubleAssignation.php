<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DoubleAssignation extends Analyzer {
    /* 5 methods */

    public function testStructures_DoubleAssignation01()  { $this->generic_test('Structures_DoubleAssignation.01'); }
    public function testStructures_DoubleAssignation02()  { $this->generic_test('Structures_DoubleAssignation.02'); }
    public function testStructures_DoubleAssignation03()  { $this->generic_test('Structures_DoubleAssignation.03'); }
    public function testStructures_DoubleAssignation04()  { $this->generic_test('Structures/DoubleAssignation.04'); }
    public function testStructures_DoubleAssignation05()  { $this->generic_test('Structures/DoubleAssignation.05'); }
}
?>