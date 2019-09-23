<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectLocalVariableCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectLocalVariableCounts01()  { $this->generic_test('Dump/CollectLocalVariableCounts.01'); }
}
?>