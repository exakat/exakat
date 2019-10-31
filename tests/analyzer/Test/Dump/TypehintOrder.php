<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TypehintOrder extends Analyzer {
    /* 1 methods */

    public function testDump_TypehintOrder01()  { $this->generic_test('Dump/TypehintOrder.01'); }
}
?>