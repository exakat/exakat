<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class GlobalUsage extends Analyzer {
    /* 2 methods */

    public function testStructures_GlobalUsage01()  { $this->generic_test('Structures_GlobalUsage.01'); }
    public function testStructures_GlobalUsage02()  { $this->generic_test('Structures/GlobalUsage.02'); }
}
?>