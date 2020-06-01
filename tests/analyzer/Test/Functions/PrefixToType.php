<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PrefixToType extends Analyzer {
    /* 2 methods */

    public function testFunctions_PrefixToType01()  { $this->generic_test('Functions/PrefixToType.01'); }
    public function testFunctions_PrefixToType02()  { $this->generic_test('Functions/PrefixToType.02'); }
}
?>