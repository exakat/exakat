<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectClassesDependencies extends Analyzer {
    /* 1 methods */

    public function testDump_CollectClassesDependencies01()  { $this->generic_test('Dump/CollectClassesDependencies.01'); }
}
?>