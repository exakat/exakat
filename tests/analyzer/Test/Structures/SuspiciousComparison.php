<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SuspiciousComparison extends Analyzer {
    /* 1 methods */

    public function testStructures_SuspiciousComparison01()  { $this->generic_test('Structures/SuspiciousComparison.01'); }
}
?>