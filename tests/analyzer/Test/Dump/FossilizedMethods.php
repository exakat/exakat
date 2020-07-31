<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FossilizedMethods extends Analyzer {
    /* 1 methods */

    public function testDump_FossilizedMethods01()  { $this->generic_test('Dump/FossilizedMethods.01'); }
}
?>