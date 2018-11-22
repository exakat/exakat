<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NoStringWithAppend extends Analyzer {
    /* 2 methods */

    public function testPhp_NoStringWithAppend01()  { $this->generic_test('Php/NoStringWithAppend.01'); }
    public function testPhp_NoStringWithAppend02()  { $this->generic_test('Php/NoStringWithAppend.02'); }
}
?>