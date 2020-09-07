<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectNativeCallsPerExpressions extends Analyzer {
    /* 1 methods */

    public function testDump_CollectNativeCallsPerExpressions01()  { $this->generic_test('Dump/CollectNativeCallsPerExpressions.01'); }
}
?>