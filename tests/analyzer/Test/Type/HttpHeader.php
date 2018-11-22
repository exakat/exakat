<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class HttpHeader extends Analyzer {
    /* 3 methods */

    public function testType_HttpHeader01()  { $this->generic_test('Type_HttpHeader.01'); }
    public function testType_HttpHeader02()  { $this->generic_test('Type/HttpHeader.02'); }
    public function testType_HttpHeader03()  { $this->generic_test('Type/HttpHeader.03'); }
}
?>