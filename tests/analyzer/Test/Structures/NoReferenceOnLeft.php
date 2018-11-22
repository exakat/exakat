<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoReferenceOnLeft extends Analyzer {
    /* 2 methods */

    public function testStructures_NoReferenceOnLeft01()  { $this->generic_test('Structures/NoReferenceOnLeft.01'); }
    public function testStructures_NoReferenceOnLeft02()  { $this->generic_test('Structures/NoReferenceOnLeft.02'); }
}
?>