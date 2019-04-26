<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class HexadecimalString extends Analyzer {
    /* 1 methods */

    public function testType_HexadecimalString01()  { $this->generic_test('Type_HexadecimalString.01'); }
}
?>