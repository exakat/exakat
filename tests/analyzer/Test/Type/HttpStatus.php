<?php

namespace Test\Type;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class HttpStatus extends Analyzer {
    /* 1 methods */

    public function testType_HttpStatus01()  { $this->generic_test('Type_HttpStatus.01'); }
}
?>