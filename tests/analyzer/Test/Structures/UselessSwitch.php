<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessSwitch extends Analyzer {
    /* 3 methods */

    public function testStructures_UselessSwitch01()  { $this->generic_test('Structures/UselessSwitch.01'); }
    public function testStructures_UselessSwitch02()  { $this->generic_test('Structures/UselessSwitch.02'); }
    public function testStructures_UselessSwitch03()  { $this->generic_test('Structures/UselessSwitch.03'); }
}
?>