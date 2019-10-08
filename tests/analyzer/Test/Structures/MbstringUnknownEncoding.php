<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MbstringUnknownEncoding extends Analyzer {
    /* 2 methods */

    public function testStructures_MbstringUnknownEncoding01()  { $this->generic_test('Structures/MbstringUnknownEncoding.01'); }
    public function testStructures_MbstringUnknownEncoding02()  { $this->generic_test('Structures/MbstringUnknownEncoding.02'); }
}
?>