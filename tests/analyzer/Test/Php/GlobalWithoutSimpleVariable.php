<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class GlobalWithoutSimpleVariable extends Analyzer {
    /* 2 methods */

    public function testPhp_GlobalWithoutSimpleVariable01()  { $this->generic_test('Php/GlobalWithoutSimpleVariable.01'); }
    public function testPhp_GlobalWithoutSimpleVariable02()  { $this->generic_test('Php/GlobalWithoutSimpleVariable.02'); }
}
?>