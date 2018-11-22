<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class RepeatedPrint extends Analyzer {
    /* 3 methods */

    public function testStructures_RepeatedPrint01()  { $this->generic_test('Structures_RepeatedPrint.01'); }
    public function testStructures_RepeatedPrint02()  { $this->generic_test('Structures/RepeatedPrint.02'); }
    public function testStructures_RepeatedPrint03()  { $this->generic_test('Structures/RepeatedPrint.03'); }
}
?>