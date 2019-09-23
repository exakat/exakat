<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectLiterals extends Analyzer {
    /* 1 methods */

    public function testDump_CollectLiterals01()  { $this->generic_test('Dump/CollectLiterals.01'); }
}
?>