<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongTypehintedName extends Analyzer {
    /* 1 methods */

    public function testFunctions_WrongTypehintedName01()  { $this->generic_test('Functions/WrongTypehintedName.01'); }
}
?>