<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectClassChanges extends Analyzer {
    /* 2 methods */

    public function testDump_CollectClassChanges01()  { $this->generic_test('Dump/CollectClassChanges.01'); }
    public function testDump_CollectClassChanges02()  { $this->generic_test('Dump/CollectClassChanges.02'); }
}
?>