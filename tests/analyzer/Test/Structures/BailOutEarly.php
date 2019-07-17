<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class BailOutEarly extends Analyzer {
    /* 8 methods */

    public function testStructures_BailOutEarly01()  { $this->generic_test('Structures/BailOutEarly.01'); }
    public function testStructures_BailOutEarly02()  { $this->generic_test('Structures/BailOutEarly.02'); }
    public function testStructures_BailOutEarly03()  { $this->generic_test('Structures/BailOutEarly.03'); }
    public function testStructures_BailOutEarly04()  { $this->generic_test('Structures/BailOutEarly.04'); }
    public function testStructures_BailOutEarly05()  { $this->generic_test('Structures/BailOutEarly.05'); }
    public function testStructures_BailOutEarly06()  { $this->generic_test('Structures/BailOutEarly.06'); }
    public function testStructures_BailOutEarly07()  { $this->generic_test('Structures/BailOutEarly.07'); }
    public function testStructures_BailOutEarly08()  { $this->generic_test('Structures/BailOutEarly.08'); }
}
?>