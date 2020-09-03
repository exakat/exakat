<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectGlobalVariables extends Analyzer {
    /* 1 methods */

    public function testDump_CollectGlobalVariables01()  { $this->generic_test('Dump/CollectGlobalVariables.01'); }
}
?>