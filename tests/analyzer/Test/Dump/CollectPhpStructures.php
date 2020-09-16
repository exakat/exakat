<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectPhpStructures extends Analyzer {
    /* 1 methods */

    public function testDump_CollectPhpStructures01()  { $this->generic_test('Dump/CollectPhpStructures.01'); }
}
?>