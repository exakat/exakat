<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectNamespaces extends Analyzer {
    /* 1 methods */

    public function testDump_CollectNamespaces01()  { $this->generic_test('Dump/CollectNamespaces.01'); }
}
?>