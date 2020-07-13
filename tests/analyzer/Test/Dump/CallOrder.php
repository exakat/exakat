<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CallOrder extends Analyzer {
    /* 1 methods */

    public function testDump_CallOrder01()  { $this->generic_test('Dump/CallOrder.01'); }
}
?>