<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NeverNegative extends Analyzer {
    /* 2 methods */

    public function testStructures_NeverNegative01()  { $this->generic_test('Structures/NeverNegative.01'); }
    public function testStructures_NeverNegative02()  { $this->generic_test('Structures/NeverNegative.02'); }
}
?>