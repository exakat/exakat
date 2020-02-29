<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CollectMbstringEncodings extends Analyzer {
    /* 2 methods */

    public function testDump_CollectMbstringEncodings01()  { $this->generic_test('Dump/CollectMbstringEncodings.01'); }
    public function testDump_CollectMbstringEncodings02()  { $this->generic_test('Dump/CollectMbstringEncodings.02'); }
}
?>