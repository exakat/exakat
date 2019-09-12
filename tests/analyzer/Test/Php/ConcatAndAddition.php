<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ConcatAndAddition extends Analyzer {
    /* 2 methods */

    public function testPhp_ConcatAndAddition01()  { $this->generic_test('Php/ConcatAndAddition.01'); }
    public function testPhp_ConcatAndAddition02()  { $this->generic_test('Php/ConcatAndAddition.02'); }
}
?>