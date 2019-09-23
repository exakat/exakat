<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectParameterCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectParameterCounts01()  { $this->generic_test('Dump/CollectParameterCounts.01'); }
}
?>