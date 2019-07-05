<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class GeneratorCannotReturn extends Analyzer {
    /* 1 methods */

    public function testFunctions_GeneratorCannotReturn01()  { $this->generic_test('Functions/GeneratorCannotReturn.01'); }
}
?>