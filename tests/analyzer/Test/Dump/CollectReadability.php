<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectReadability extends Analyzer {
    /* 1 methods */

    public function testDump_CollectReadability01()  { $this->generic_test('Dump/CollectReadability.01'); }
}
?>