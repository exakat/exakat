<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class BadTypehintRelay extends Analyzer {
    /* 4 methods */

    public function testFunctions_BadTypehintRelay01()  { $this->generic_test('Functions/BadTypehintRelay.01'); }
    public function testFunctions_BadTypehintRelay02()  { $this->generic_test('Functions/BadTypehintRelay.02'); }
    public function testFunctions_BadTypehintRelay03()  { $this->generic_test('Functions/BadTypehintRelay.03'); }
    public function testFunctions_BadTypehintRelay04()  { $this->generic_test('Functions/BadTypehintRelay.04'); }
}
?>