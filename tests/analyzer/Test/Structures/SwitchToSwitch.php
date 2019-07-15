<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SwitchToSwitch extends Analyzer {
    /* 6 methods */

    public function testStructures_SwitchToSwitch01()  { $this->generic_test('Structures/SwitchToSwitch.01'); }
    public function testStructures_SwitchToSwitch02()  { $this->generic_test('Structures/SwitchToSwitch.02'); }
    public function testStructures_SwitchToSwitch03()  { $this->generic_test('Structures/SwitchToSwitch.03'); }
    public function testStructures_SwitchToSwitch04()  { $this->generic_test('Structures/SwitchToSwitch.04'); }
    public function testStructures_SwitchToSwitch05()  { $this->generic_test('Structures/SwitchToSwitch.05'); }
    public function testStructures_SwitchToSwitch06()  { $this->generic_test('Structures/SwitchToSwitch.06'); }
}
?>