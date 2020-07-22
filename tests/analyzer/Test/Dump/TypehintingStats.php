<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TypehintingStats extends Analyzer {
    /* 3 methods */

    public function testDump_TypehintingStats01()  { $this->generic_test('Dump/TypehintingStats.01'); }
    public function testDump_TypehintingStats02()  { $this->generic_test('Dump/TypehintingStats.02'); }
    public function testDump_TypehintingStats03()  { $this->generic_test('Dump/TypehintingStats.03'); }
}
?>