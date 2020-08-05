<?php

namespace Test\Patterns;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AbstractAway extends Analyzer {
    /* 3 methods */

    public function testPatterns_AbstractAway01()  { $this->generic_test('Patterns/AbstractAway.01'); }
    public function testPatterns_AbstractAway02()  { $this->generic_test('Patterns/AbstractAway.02'); }
    public function testPatterns_AbstractAway03()  { $this->generic_test('Patterns/AbstractAway.03'); }
}
?>