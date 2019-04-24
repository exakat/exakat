<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NextMonthTrap extends Analyzer {
    /* 2 methods */

    public function testStructures_NextMonthTrap01()  { $this->generic_test('Structures/NextMonthTrap.01'); }
    public function testStructures_NextMonthTrap02()  { $this->generic_test('Structures/NextMonthTrap.02'); }
}
?>