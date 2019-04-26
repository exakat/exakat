<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Noscream extends Analyzer {
    /* 1 methods */

    public function testStructures_Noscream01()  { $this->generic_test('Structures_Noscream.01'); }
}
?>