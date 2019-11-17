<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectClassChildren extends Analyzer {
    /* 1 methods */

    public function testDump_CollectClassChildren01()  { $this->generic_test('Dump/CollectClassChildren.01'); }
}
?>