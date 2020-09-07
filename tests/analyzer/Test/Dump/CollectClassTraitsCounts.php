<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectClassTraitsCounts extends Analyzer {
    /* 1 methods */

    public function testDump_CollectClassTraitsCounts01()  { $this->generic_test('Dump/CollectClassTraitsCounts.01'); }
}
?>