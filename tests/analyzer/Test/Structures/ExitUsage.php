<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ExitUsage extends Analyzer {
    /* 4 methods */

    public function testStructures_ExitUsage01()  { $this->generic_test('Structures_ExitUsage.01'); }
    public function testStructures_ExitUsage02()  { $this->generic_test('Structures_ExitUsage.02'); }
    public function testStructures_ExitUsage03()  { $this->generic_test('Structures/ExitUsage.03'); }
    public function testStructures_ExitUsage04()  { $this->generic_test('Structures/ExitUsage.04'); }
}
?>