<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SolveTraitMethods extends Analyzer {
    /* 1 methods */

    public function testComplete_SolveTraitMethods01()  { $this->generic_test('Complete/SolveTraitMethods.01'); }
}
?>