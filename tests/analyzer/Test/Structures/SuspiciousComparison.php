<?php

namespace Test\Structures;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class SuspiciousComparison extends Analyzer {
    /* 1 methods */

    public function testStructures_SuspiciousComparison01()  { $this->generic_test('Structures/SuspiciousComparison.01'); }
}
?>