<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectParameterCounts extends Analyzer {
    /* 3 methods */

    public function testDump_CollectParameterCounts01()  { $this->generic_test('Dump/CollectParameterCounts.01'); }
    public function testDump_CollectParameterCounts02()  { $this->generic_test('Dump/CollectParameterCounts.02'); }
    public function testDump_CollectParameterCounts03()  { $this->generic_test('Dump/CollectParameterCounts.03'); }
}
?>