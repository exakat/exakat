<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectPropertyCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectPropertyCounts01()  { $this->generic_test('Dump/CollectPropertyCounts.01'); }
}
?>