<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NewOrder extends Analyzer {
    /* 1 methods */

    public function testDump_NewOrder01()  { $this->generic_test('Dump/NewOrder.01'); }
}
?>