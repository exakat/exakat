<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class FossilizedMethods extends Analyzer {
    /* 2 methods */

    public function testDump_FossilizedMethods01()  { $this->generic_test('Dump/FossilizedMethods.01'); }
    public function testDump_FossilizedMethods02()  { $this->generic_test('Dump/FossilizedMethods.02'); }
}
?>