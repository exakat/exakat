<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoChoice extends Analyzer {
    /* 5 methods */

    public function testStructures_NoChoice01()  { $this->generic_test('Structures/NoChoice.01'); }
    public function testStructures_NoChoice02()  { $this->generic_test('Structures/NoChoice.02'); }
    public function testStructures_NoChoice03()  { $this->generic_test('Structures/NoChoice.03'); }
    public function testStructures_NoChoice04()  { $this->generic_test('Structures/NoChoice.04'); }
    public function testStructures_NoChoice05()  { $this->generic_test('Structures/NoChoice.05'); }
}
?>