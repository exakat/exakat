<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectDefinitionsStats extends Analyzer {
    /* 1 methods */

    public function testDump_CollectDefinitionsStats01()  { $this->generic_test('Dump/CollectDefinitionsStats.01'); }
}
?>