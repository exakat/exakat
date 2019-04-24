<?php

namespace Test\Patterns;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CourrierAntiPattern extends Analyzer {
    /* 1 methods */

    public function testPatterns_CourrierAntiPattern01()  { $this->generic_test('Patterns/CourrierAntiPattern.01'); }
}
?>