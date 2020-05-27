<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectClassDepth extends Analyzer {
    /* 2 methods */

    public function testDump_CollectClassDepth01()  { $this->generic_test('Dump/CollectClassDepth.01'); }
    public function testDump_CollectClassDepth02()  { $this->generic_test('Dump/CollectClassDepth.02'); }
}
?>