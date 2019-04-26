<?php

namespace Test\Patterns;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DependencyInjection extends Analyzer {
    /* 1 methods */

    public function testPatterns_DependencyInjection01()  { $this->generic_test('Patterns/DependencyInjection.01'); }
}
?>