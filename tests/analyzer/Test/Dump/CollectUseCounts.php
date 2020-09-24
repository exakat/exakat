<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectUseCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectUseCounts01()  { $this->generic_test('Dump/CollectUseCounts.01'); }
}
?>