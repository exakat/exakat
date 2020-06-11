<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectClassConstantCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectClassConstantCounts01()  { $this->generic_test('Dump/CollectClassConstantCounts.01'); }
}
?>