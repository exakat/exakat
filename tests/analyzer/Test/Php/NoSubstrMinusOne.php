<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoSubstrMinusOne extends Analyzer {
    /* 2 methods */

    public function testPhp_NoSubstrMinusOne01()  { $this->generic_test('Php/NoSubstrMinusOne.01'); }
    public function testPhp_NoSubstrMinusOne02()  { $this->generic_test('Php/NoSubstrMinusOne.02'); }
}
?>