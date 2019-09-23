<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CyclomaticComplexity extends Analyzer {
    /* 1 methods */

    public function testDump_CyclomaticComplexity01()  { $this->generic_test('Dump/CyclomaticComplexity.01'); }
}
?>