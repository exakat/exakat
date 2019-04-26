<?php

namespace Test\Psr;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Psr7Usage extends Analyzer {
    /* 1 methods */

    public function testPsr_Psr7Usage01()  { $this->generic_test('Psr/Psr7Usage.01'); }
}
?>