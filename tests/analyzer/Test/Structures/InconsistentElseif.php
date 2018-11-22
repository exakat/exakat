<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class InconsistentElseif extends Analyzer {
    /* 6 methods */

    public function testStructures_InconsistentElseif01()  { $this->generic_test('Structures/InconsistentElseif.01'); }
    public function testStructures_InconsistentElseif02()  { $this->generic_test('Structures/InconsistentElseif.02'); }
    public function testStructures_InconsistentElseif03()  { $this->generic_test('Structures/InconsistentElseif.03'); }
    public function testStructures_InconsistentElseif04()  { $this->generic_test('Structures/InconsistentElseif.04'); }
    public function testStructures_InconsistentElseif05()  { $this->generic_test('Structures/InconsistentElseif.05'); }
    public function testStructures_InconsistentElseif06()  { $this->generic_test('Structures/InconsistentElseif.06'); }
}
?>