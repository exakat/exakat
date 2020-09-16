<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectAtomCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectAtomCounts01()  { $this->generic_test('Dump/CollectAtomCounts.01'); }
}
?>