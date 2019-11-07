<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectClassInterfaceCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectClassInterfaceCounts01()  { $this->generic_test('Dump/CollectClassInterfaceCounts.01'); }
}
?>