<?php

namespace Test\Psr;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Psr11Usage extends Analyzer {
    /* 1 methods */

    public function testPsr_Psr11Usage01()  { $this->generic_test('Psr/Psr11Usage.01'); }
}
?>