<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectParameterNames extends Analyzer {
    /* 2 methods */

    public function testDump_CollectParameterNames01()  { $this->generic_test('Dump/CollectParameterNames.01'); }
    public function testDump_CollectParameterNames02()  { $this->generic_test('Dump/CollectParameterNames.02'); }
}
?>