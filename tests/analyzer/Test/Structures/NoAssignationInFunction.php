<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoAssignationInFunction extends Analyzer {
    /* 2 methods */

    public function testStructures_NoAssignationInFunction01()  { $this->generic_test('Structures/NoAssignationInFunction.01'); }
    public function testStructures_NoAssignationInFunction02()  { $this->generic_test('Structures/NoAssignationInFunction.02'); }
}
?>