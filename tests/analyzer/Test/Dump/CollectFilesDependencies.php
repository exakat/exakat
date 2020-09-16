<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectFilesDependencies extends Analyzer {
    /* 1 methods */

    public function testDump_CollectFilesDependencies01()  { $this->generic_test('Dump/CollectFilesDependencies.01'); }
}
?>