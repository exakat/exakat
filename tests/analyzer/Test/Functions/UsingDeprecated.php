<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UsingDeprecated extends Analyzer {
    /* 2 methods */

    public function testFunctions_UsingDeprecated01()  { $this->generic_test('Functions/UsingDeprecated.01'); }
    public function testFunctions_UsingDeprecated02()  { $this->generic_test('Functions/UsingDeprecated.02'); }
}
?>