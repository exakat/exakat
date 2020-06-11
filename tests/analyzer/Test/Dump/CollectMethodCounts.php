<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectMethodCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectMethodCounts01()  { $this->generic_test('Dump/CollectMethodCounts.01'); }
}
?>